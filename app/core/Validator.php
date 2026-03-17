<?php
// /kwetu_con/app/core/Validator.php

/**
 * Validateur de données pour KWETU CON
 */
class Validator {
    
    private $errors = [];
    private $data = [];
    
    /**
     * Valider les données selon les règles
     */
    public function validate($data, $rules) {
        $this->data = $data;
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $fieldRules = explode('|', $fieldRules);
            
            foreach ($fieldRules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Appliquer une règle de validation
     */
    private function applyRule($field, $rule) {
        // Règle avec paramètre (ex: min:18)
        if (strpos($rule, ':') !== false) {
            list($ruleName, $parameter) = explode(':', $rule, 2);
        } else {
            $ruleName = $rule;
            $parameter = null;
        }
        
        $value = $this->data[$field] ?? null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "Le champ {$field} est requis");
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "Le champ {$field} doit être un email valide");
                }
                break;
                
            case 'min':
                if (!empty($value) && strlen($value) < $parameter) {
                    $this->addError($field, "Le champ {$field} doit contenir au moins {$parameter} caractères");
                }
                break;
                
            case 'max':
                if (!empty($value) && strlen($value) > $parameter) {
                    $this->addError($field, "Le champ {$field} ne doit pas dépasser {$parameter} caractères");
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "Le champ {$field} doit être numérique");
                }
                break;
                
            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "Le champ {$field} doit être un entier");
                }
                break;
                
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->addError($field, "Le champ {$field} doit être une URL valide");
                }
                break;
                
            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, "Le champ {$field} doit être une date valide");
                }
                break;
                
            case 'age_min':
                if (!empty($value)) {
                    $age = date_diff(date_create($value), date_create('today'))->y;
                    if ($age < $parameter) {
                        $this->addError($field, "Vous devez avoir au moins {$parameter} ans");
                    }
                }
                break;
                
            case 'phone':
                if (!empty($value) && !preg_match('/^[0-9+\-\s()]+$/', $value)) {
                    $this->addError($field, "Le champ {$field} doit être un numéro de téléphone valide");
                }
                break;
                
            case 'password':
                if (!empty($value)) {
                    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
                    if (!preg_match($pattern, $value)) {
                        $this->addError($field, "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial");
                    }
                }
                break;
                
            case 'same':
                if (!empty($value) && $value !== ($this->data[$parameter] ?? null)) {
                    $this->addError($field, "Les champs {$field} et {$parameter} doivent correspondre");
                }
                break;
                
            case 'unique':
                // À implémenter avec la base de données
                break;
                
            case 'in':
                $options = explode(',', $parameter);
                if (!empty($value) && !in_array($value, $options)) {
                    $this->addError($field, "Le champ {$field} n'est pas valide");
                }
                break;
        }
    }
    
    /**
     * Ajouter une erreur
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $this->errors[$field][] = $message;
    }
    
    /**
     * Obtenir toutes les erreurs
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Obtenir la première erreur
     */
    public function getFirstError() {
        if (empty($this->errors)) {
            return null;
        }
        
        $firstField = array_key_first($this->errors);
        return $this->errors[$firstField][0] ?? null;
    }
    
    /**
     * Vérifier si une erreur existe pour un champ
     */
    public function hasError($field) {
        return isset($this->errors[$field]);
    }
    
    /**
     * Obtenir les erreurs pour un champ
     */
    public function getError($field) {
        return $this->errors[$field][0] ?? null;
    }
}