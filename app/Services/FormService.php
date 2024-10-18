<?php

namespace App\Services;

use App\Enums\Date;
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
        
        return $this->recursiveToArray($formattedForm->toArray());
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

            if ($field->type == "select") {
                $field = $this->setFieldOptions($field);
            }

            $formattedForm->get($field->card_order)['fields']->push($this->formatField($field));
        }

        return $formattedForm;
    }

    public function formatField($field)
    {
        $field = $this->applyMaskToField($field);
        $field = $this->setFieldOptions($field);
        return $this->setFieldConfigs($field);
    }

    private function applyMaskToField($field)
    {
        $phones = array_map(fn($case) => $case->value, Telephone::cases());
        $dates = array_map(fn($case) => $case->value, Date::cases());
        switch ($field->column) {
            case in_array($field->column, $phones):
                $field->type = "phone";
                return $field;
            case in_array($field->column, $dates):
                $field->mask = "99/99/9999";
                return $field;
            case 'cpf':
                $field->mask = "999.999.999-99";
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

    public function setFieldOptions($field)
    {
        switch ($field->column) {
            case 'idPessoaTipo':
                dd($this->formRepository->getPersonTypes());
        }
    }

    public function recursiveToArray($item)
    {
        if ($item instanceof \Illuminate\Support\Collection || $item instanceof \Illuminate\Database\Eloquent\Collection) {
            return $item->map(fn($value) => $this->recursiveToArray($value))->toArray();
        } elseif ($item instanceof \Illuminate\Database\Eloquent\Model) {
            return $item->toArray();
        } elseif (is_array($item)) {
            return array_map(fn($value) => $this->recursiveToArray($value), $item);
        }

        return $item;
    }
}