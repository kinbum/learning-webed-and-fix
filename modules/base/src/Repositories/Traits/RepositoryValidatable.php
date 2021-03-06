<?php namespace App\Module\Base\Repositories\Traits;

/**
 * Class RepositoryRules
 * TODO Validate model before create/update
 * NOTE: just add model rules. No business logic here
 * @author Tedozi Manson <duyphan.developer@gmail.com>
 * @property array $editableFields
 */
trait RepositoryValidatable
{
    protected $ruleErrors = [];

    protected $rules = [];

    /**
     * @var \App\Module\Base\Services\Validator
     */
    protected $validator;

    /**
     * @return array
     */
    public function getModelRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function setModelRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function expandModelRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * @return array
     */
    public function getEditableFields()
    {
        return (property_exists($this, 'editableFields')) ? $this->editableFields : ['*'];
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setEditableFields(array $fields)
    {
        $this->editableFields = $fields;

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function expandEditableFields(array $fields)
    {
        $this->editableFields = array_merge($this->editableFields, $fields);

        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function unsetEditableFields($fields)
    {
        if (!is_array($fields)) {
            $fields = func_get_args();
        }

        $this->editableFields = $this->getEditableFields();

        foreach ($fields as $key => $field) {
            unset($this->editableFields[array_search($field, $this->editableFields)]);
        }

        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function unsetNotEditableFields(array &$data)
    {
        $editableCollection = collect($this->editableFields);
        if ($editableCollection->contains('*')) {
            return [];
        }
        $cannotEdit = [];
        foreach ($data as $key => $row) {
            if (!$editableCollection->contains($key)) {
                $cannotEdit[] = $key;
                unset($data[$key]);
            }
        }
        return $cannotEdit;
    }

    /**
     * @param \App\Module\Base\Models\EloquentBase $object
     * @param $data
     */
    protected function unsetNotChangedData($object, &$data)
    {
        $dataNotChanged = [];
        foreach ($data as $key => $row) {
            if (isset($object->{$key}) && $object->{$key} == $data[$key]) {
                $dataNotChanged[] = $key;

                unset($data[$key]);
            }
        }
        return $dataNotChanged;
    }

    /**
     * Validate model
     * @param $data
     * @param bool $justUpdateSomeFields
     * @return bool
     */
    public function validateModel($data, $justUpdateSomeFields = false)
    {
        if ($justUpdateSomeFields == true) {
            $originalRules = $this->rules;
            $rules = [];
            foreach ($data as $key => $row) {
                if (isset($originalRules[$key])) {
                    $rules[$key] = $originalRules[$key];
                }
            }
        } else {
            $rules = $this->rules;
        }

        $this->ruleErrors = [];

        $result = $this->getValidatorInstance()->make($data, $rules);

        if (!$result) {
            $this->ruleErrors = $this->getValidatorInstance()->getErrors();
            return false;
        }

        return true;
    }

    /**
     * Get error messages
     * @return array
     */
    public function getRuleErrors()
    {
        return $this->ruleErrors;
    }

    /**
     * Get error messages - no key
     * @return array
     */
    public function getRuleErrorMessages()
    {
        $messages = [];
        foreach ($this->ruleErrors as $key => $row) {
            foreach ($row as $keyRow => $valueRow) {
                $messages[] = $valueRow;
            }
        }

        return $messages;
    }

    /**
     * @return \App\Module\Base\Services\Validator
     */
    public function getValidatorInstance()
    {
        if (!$this->validator) {
            $this->validator = new \App\Module\Base\Services\Validator();
        }
        return $this->validator;
    }
}
