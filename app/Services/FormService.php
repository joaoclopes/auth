<?php

namespace App\Services;

use App\Enums\Date;
use App\Enums\Telephone;
use App\Repositories\FormRepository;
use App\Repositories\OrganizationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

class FormService
{
    public function __construct(protected FormRepository $formRepository, protected OrganizationRepository $orgRepository)
    {
    }

    public function getForm()
    {
        $fields = $this->formRepository->getForm();
        $formattedForm = $this->formatForm($fields);

        return $formattedForm->toArray();
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
                $field['options'] = $this->setFieldOptions($field);
            }

            $formattedForm->get($field->card_order)['fields']->push($this->formatField($field));
        }

        return $formattedForm;
    }

    public function formatField($field)
    {
        $field = $this->applyMaskToField($field);
        $field = $this->setFieldConfigs($field);
        return $field;
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
        $config = isset($field->config) ? json_decode($field->config) : null;
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

    private function translateOptions($options)
    {
        $translatedOptions = [];
        foreach ($options as $opt) {
            if (is_array($opt)) {
                array_push($translatedOptions, ['value' => $opt['value'], 'label' => __('select.' . $opt['label'])]);
                continue;
            }
            array_push($translatedOptions, ['value' => $opt->value, 'label' => __('select.' . $opt->label)]);
        }

        return $translatedOptions;
    }

    private function setFieldOptions($field)
    {
        switch ($field->column) {
            // Contempla apenas os selects que serao mostrados para o publico
            case 'idPessoaTipo':
                $options = $this->formRepository->getRegisterTypes();
                break;
            case 'idSexo':
                $options = $this->formRepository->getGenderTypes();
                break;
            case 'idEstadoCivil':
                return $this->formRepository->getRelationStatus();
            case 'idEstadoReligioso':
                $options = $this->formRepository->getReligiousStates();
                break;
            case 'tipoSanguineo':
                return $this->formRepository->getBloodTypes();
            case 'idEscolaridade':
                $options = $this->formRepository->getSchoolingTypes();
                break;
            case 'idioma':
                $options = $this->formRepository->getLanguages();
                break;
            case 'profissao':
                return $this->formRepository->getProfessions();
            case 'idTipoSugerido':
                return $this->formRepository->getSugestedTypes();
            case 'idTipoDocumento':
                return $this->formRepository->getDocumentTypes();
            case 'idEstadoUf':
                return $this->formRepository->getStates();
            case 'idPessoaDependente':
                return $this->formRepository->getDependentPersonTypes();
            case 'idOperadora':
            case 'idOperadoraOutro':
                return $this->formRepository->getOperatorContacts();
            case 'idStatusPessoa':
                return $this->formRepository->getPersonStatuses();
            case 'idComoConheceu':
                return $this->formRepository->getHowDoYouKnowTypes();
            case 'idDom':
                return $this->formRepository->getGifts();
            case 'codigo2':
                return $this->formRepository->getCode2Statuses();
        }

        return isset($options) ? $this->translateOptions($options) : [];
    }
}