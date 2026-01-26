<?php

namespace App\Core;

class Validator
{
    protected $errors = [];

    public function validate(array $data, array $rules)
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            foreach ($rulesArray as $rule) {
                $value = $data[$field] ?? null;
                $this->applyRule($field, $value, $rule, $data);
            }
        }

        return $this->errors;
    }

    protected function applyRule($field, $value, $rule, $data)
    {
        if ($rule === 'required') {
            if (empty($value) && $value !== '0') {
                $this->addError($field, 'is required');
            }
        } elseif ($rule === 'email') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->addError($field, 'must be a valid email');
            }
        } elseif (strpos($rule, 'min:') === 0) {
            $min = (int) substr($rule, 4);
            if (strlen($value) < $min) {
                $this->addError($field, "must be at least $min characters");
            }
        } elseif (strpos($rule, 'max:') === 0) {
            $max = (int) substr($rule, 4);
            if (strlen($value) > $max) {
                $this->addError($field, "must not exceed $max characters");
            }
        } elseif ($rule === 'confirmed') {
            if ($value !== ($data[$field . '_confirmation'] ?? null)) {
                $this->addError($field, 'does not match confirmation');
            }
        }
    }

    protected function addError($field, $message)
    {
        $this->errors[$field][] = ucfirst(str_replace('_', ' ', $field)) . ' ' . $message;
    }
}
