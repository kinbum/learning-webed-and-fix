<?php
if (!function_exists('parse_custom_fields_raw_data')) {
    /**
     * @param $jsonString
     * @return array
     */
    function parse_custom_fields_raw_data($jsonString)
    {
        try {
            $fieldGroups = json_decode($jsonString);
        } catch (\Exception $exception) {
            return [];
        }

        $result = [];
        foreach ($fieldGroups as $fieldGroup) {
            foreach ($fieldGroup->items as $item) {
                $result[] = $item;
            }
        }
        return $result;
    }
}

if (!function_exists('custom_field_rules')) {
    /**
     * @return \App\Plugins\CustomFields\Support\CustomFieldRules
     */
    function custom_field_rules()
    {
        return \App\Plugins\CustomFields\Support\Facades\CustomFieldRules::getFacadeRoot();
    }
}

if (!function_exists('render_custom_fields')) {
    /**
     * @return \App\Plugins\CustomFields\Support\RenderCustomFields
     */
    function render_custom_fields()
    {
        return \App\Plugins\CustomFields\Support\Facades\RenderCustomFields::getFacadeRoot();
    }
}

if (!function_exists('set_custom_field_rules')) {
    /**
     * @param array $rules
     * @return mixed
     */
    function set_custom_field_rules(array $rules)
    {
        return render_custom_fields()->setRules($rules);
    }
}

if (!function_exists('add_custom_field_rules')) {
    /**
     * @param array|string $ruleName
     * @param $value
     * @return mixed
     */
    function add_custom_field_rules($ruleName, $value = null)
    {
        return render_custom_fields()->addRules($ruleName, $value);
    }
}


if (!function_exists('get_custom_field_boxes')) {
    /**
     * @param string $modelName
     * @param int $modelId
     * @return mixed
     */
    function get_custom_field_boxes($modelName, $modelId)
    {
        if (is_object($modelName)) {
            $modelName = get_class($modelName);
        }
        return render_custom_fields()->exportCustomFieldsData((string)$modelName, $modelId);
    }
}
