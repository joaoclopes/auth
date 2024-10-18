<?php

namespace App\Services;

use App\Enums\Telephone;
use App\Repositories\FormRepository;
use Illuminate\Database\Eloquent\Collection;

class FormService
{
    public function __construct(protected FormRepository $formRepository)
    {
    }

    public function getForm()
    {
        $fields = $this->formRepository->getForm();
        $formattedForm = $this->formatForm($fields);
        $encoded = json_encode($formattedForm);
        dd(json_decode($encoded));
    }

    public function formatForm($fields)
    {
        $formattedForm = new Collection();

        foreach ($fields as $field) {
            if (!$formattedForm->has($field->card_order)) {
                $formattedForm->put($field->card_order, [
                    'label' => $field->form_label,
                    'fields' => new Collection()
                ]);
            }

            $formattedForm->get($field->card_order)['fields']->push($this->formatField($field));
        }

        // Transformando a coleção externa em array e as internas também
        $formattedArray = json_encode($formattedForm);
        dd(json_decode($formattedForm));

        // Itera sobre cada item e transforma os fields em arrays
        // foreach ($formattedArray as &$item) {
        //     $item['fields'] = $item['fields']->toArray();
        // }

        return $formattedArray;
    }

    public function formatField($field)
    {
        $formattedField = $this->applyMaskToField($field);
        return $this->setFieldConfigs($formattedField);
    }

    private function applyMaskToField($field)
    {
        $phones = array_map(fn($case) => $case->value, Telephone::cases());
        switch ($field->column) {
            case in_array($field->column, $phones):
                $field->type = "phone";
                return $field;
            case 'cpf':
                $field->mask = '999.999.999-99';
                return $field;
        }
        return $field;
    }

    private function setFieldConfigs($field)
    {
        $newConfig = [];
        $config = json_decode($field->config);
        unset($field->config);
        if ($field->required) {
            $newConfig['required'] = "Preencha o campo $field->label";
            unset($field->required);
        }

        if (isset($config->tamanho) && $config->tamanho) {
            $newConfig['max'] = $config->tamanho;
        };

        if (isset($config->uppercase) && $config->uppercase) {
            $field->uppercase = $config->uppercase;
        }

        if (!empty($newConfig)) {
            $field['config'] = $newConfig;
        }

        return $field;
    }
}