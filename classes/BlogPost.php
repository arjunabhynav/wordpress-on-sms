<?php

class BlogPost
{
    private $postTitle;
    private $postContent;

    private $tags;
    private $keywords;
    private $categories;

    private $blog;
    private $username;
    private $password;

    public $status;

    public function __construct($credentials, $message)
    {
        $this->blog = $credentials['blog'].'/xmlrpc.php';
        $this->username = $credentials['username'];
        $this->password = $credentials['password'];
        if($this->validateFormat($message))
        {
            list($this->postTitle, $this->postContent) = explode('>', $message, 2);
            $this->postTitle = trim($this->postTitle);
            $this->postContent = trim($this->postContent);
            if($this->testConnection())
            {
                $this->post();
                $this->status = true;
            }
            else
            {
                $this->status = false;
            }
        }
        else
        {
            $this->status = false;
        }
    }

    private function validateFormat($message)
    {
        return preg_match('/^(.+)(>)(.+)/',$message);
    }

    private function makeRequest($requestName, $parameters)  
    {  
        $request = xmlrpc_encode_request($requestName, $parameters);  
        $session = curl_init();  
        curl_setopt($session, CURLOPT_POSTFIELDS, $request);  
        curl_setopt($session, CURLOPT_URL, $this->blog);  
        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($session, CURLOPT_TIMEOUT, 1);  
        $results = curl_exec($session);  
        curl_close($session);  
        return $results;  
    }

    private function testConnection()
    {
        $parameters = array();
        if(strpos($this->makeRequest('demo.sayHello',$parameters), 'Hello!'))
            return true;
        else
            return false;
    }

    private function post()
    {  
        $content = array(  
            'title' => $this->postTitle,  
            'description' => $this->postContent,  
            'post_type' => 'post'
        );  
        $parameters = array(0, $this->username, $this->password, $content, true);  
        $this->makeRequest('metaWeblog.newPost',$parameters); 
    }

}
