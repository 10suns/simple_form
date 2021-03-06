<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DataClasses;
use Respect\Validation\Validator as v;

abstract class Form
{
    public $model = null;

    public $validate = array();

    /*
     * field_name =>
     *      optional => true|false
     *      rules =>
     *          rule1 =>
     *              name => 'name of rule'
     *              arguments => array()
     *              message => 'error message' (use default value if is not set)
     */
    public $validation_rules = array();

    public function __construct() {
        $this->csrf_token = $this->generate_token();
    }

    public function dispatch() {
        session_start();
        if ($__SERVER['REQUEST_METHOD'] === 'POST'){
            // CSRF check
            $csrf_safe = !empty(filter_input(INPUT_POST, 'csrf_token')) && filter_input(INPUT_POST, 'csrf_token') === $_SESSION['csrf_token'];
            $_SESSION['csrf_token'] = $this->csrf_token;
            if (!$csrf_safe) {

            }
            if (!$this->validate()) {

            }
            call_user_func(array($this, filter_input(INPUT_POST, 'mode')));
            $this->render(filter_input(INPUT_POST, 'mode'));
        }
    }

    public function confirm() {

    }

    public function complete() {

    }

    protected function validate() {
        $data = filter_input(INPUT_POST, $this->model);
        $error = false;
        foreach($this->validation_rules as $field => $config){
            $validator = new v;
            $validator->addRules($config['rules']);
            if ($config['optional']){
                $this->validate[$field] = v::optional($validator);
            } else {
                $this->validate[$field] = $validator;
            }
            $error = $this->validate[$field]->validate($data[$field]);
        }

        return $error;
    }

    protected function generate_token() {
        return hash('sha256', $_SERVER['REMOTE_ADDR'].time().rand());
    }

    protected function render($template){

    }
}