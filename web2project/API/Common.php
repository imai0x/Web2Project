<?php

abstract class web2project_API_Common {

    protected $app       = null;
    protected $module    = null;

    protected $id        = 0;
    protected $params    = array();

    protected $key       = '';
    protected $obj       = '';
    protected $classname = '';

    protected $AppUI     = null;
    protected $resources = array();

    abstract public function process();

    public function __construct(Slim $app, $module, $id = 0)
    {
        $this->app       = $app;
        $this->module    = $module;
        $this->key       = unPluralize($this->module).'_id';
        $this->id        = $id;

        return $this->init();
    }

    protected function init()
    {
        $this->AppUI = new w2p_Core_CAppUI();
        $this->resources = $this->AppUI->getActiveModules();

        if(isset($this->resources[$this->module])) {
            $this->classname = getClassName($this->module);
            $this->obj       = new $this->classname;
            $this->params    = $this->app->request()->params();
        } else {
            $this->app->response()->status(404);
        }

        return $this->app;
    }

    /*
     * It's easy to know which modules this module/object is dependent on.
     */
    protected function setSuperResources()
    {
        $resources = array();

        $fields  = get_class_vars($this->classname);
        foreach($fields as $field => $value) {
            $id = $this->obj->{$field};
            $last_underscore = strrpos($field, '_') + 1;
            $suffix = ($last_underscore !== false) ? substr($field, $last_underscore) : $field;
            unset($fields[$field]);
            $fields[w2p_pluralize($suffix)] = $id;
        }
//TODO: figure out what to do with _parents
        $modules = $this->AppUI->getActiveModules();
        foreach($fields as $field => $value) {
            if(isset($modules[$field]) && $value) {
                $resources[$field] = array('name' => $modules[$field], 'href' => "/$field/".$value);
            }
        }

        return $resources;
    }

    /*
     * TODO: .. but how do we figure out which modules/objects are dependent on it?
     *
     * Do we need a hook_register?
     */
    protected function setSubResources()
    {
        $resources = array();

        $modules = $this->AppUI->getActiveModules();
        foreach

        return $resources;
    }
}