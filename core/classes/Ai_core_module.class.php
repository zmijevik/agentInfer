<?php

abstract class Ai_core_module
{

    //store all of the associated models in an array
    protected $models;
    
    //store an array of observers
    protected $observers = array();

    //the name of this module
    protected $module_name;

    //perform initialization 
    abstract public function init();

    //perform an action on a set of input data
    abstract public function perform($action_name, $input);

    //evaulate input data 
    abstract public function evaluate($input);

    //the exploration function 
    abstract public function explore();

    //save the state
    abstract public function save_state();

    //notify the subject
    abstract public function notify();

    abstract public function install();

    protected function _register_models()
    {
        $models_dir = dirname(__FILE__) . '/models/';

        foreach(glob($models_dir . '*_model.class.php') as $filename)
        {
            $model_filename = str_replace($models_dir,'',$filename);

            $model_name = str_replace('.class.php','',$model_filename);

            $model_obj = new $model_name($connection_args);

            $model_key = str_replace('_model','',$model_name);

            ModelRegistry::register($model_key, $model_obj);
        }
    }

    public function getName()
    {
        return $this->module_name;
    }

    //add observer
    public function attach_observer(Ai_core_module $observer) {
        $this->observers[$observer->getName()] = $observer;
    }

    //remove observer
    public function detach_observer(Ai_core_module $observer) {

        $key = array_key_exists($observer->getName(),$this->observers);

        if($key){
            unset($this->observers[$observe->getName()]);
        }
    }

    //notify observers(or some of them)
    public function notify_observers() {
        foreach ($this->observers as $value) {
            $value->notify($this);
        }
    }

}