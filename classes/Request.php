<?php

class Request
{
    public $mobile;
    public $keyword;
    public $message;

    public function __construct($variables)
    {
        $this->mobile = $variables['mobile'];
        if(substr($variables['message'],0,4) == '.set')
        {
            $this->keyword = 'set';
            $this->message = trim(substr($variables['message'],4));
        }
        else if(substr($variables['message'],0,9) == '.register')
        {
            $this->keyword = 'register';
            $this->message = trim(substr($variables['message'],9));
        }
        else
        {
            $this->keyword = 'post';
            $this->message = $variables['message'];
        }
    }

    public function process()
    {
        if($this->keyword == 'register')
        {
            $user = new User($this->mobile);
            return $user->setBlog($this->message);
        }
        else if($this->keyword == 'set')
        {
            $user = new User($this->mobile);
            return $user->setUserPass($this->message);
        }
        else if($this->keyword == 'post')
        {
            $user = new User($this->mobile);
            return $user->newBlogPost($this->message);
        }
    }

}
