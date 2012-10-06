<?php

class User
{
    public $mobile;

    private $blog;
    private $username;
    private $password;

    public function __construct($mobile)
    {
        $this->mobile = $mobile;
        $this->blog = NULL;
        $this->username = NULL;
        $this->password = NULL;
        if($this->userExists())
        {
            $user = $this->getUser();
            $this->blog = $user['blog'];
            $this->username = $user['username'];
            $this->password = $user['password'];
        }
        else
        {
            $this->createUser();
        }
    }

    private function userExists()
    {     
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = 'select count(*)
            from users
            where mobile=?';
        $statement = $mysql->prepare($query);
        $statement->bind_param('s',$this->mobile);
        $statement->execute();
        $statement->bind_result($count);
        $statement->fetch();
        $statement->close();
        $mysql->close();
        if($count==0)
            return false;
        else
            return true;
    }

    private function createUser()
    {
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = 'insert into users (
            mobile,
            blog,
            username,
            password
        ) values (?,?,?,?)';
        $statement = $mysql->prepare($query);
        $statement->bind_param('ssss',
            $this->mobile,
            $this->blog,
            $this->username,
            $this->password);
        $statement->execute();
        $statement->close();
        $mysql->close();
    }

    private function getUser()
    {
        $user = array(
            'blog'=>'',
            'username'=>'',
            'password'=>'');
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = 'select 
            blog,
            username,
            password
            from users
            where mobile=?';
        $statement = $mysql->prepare($query);
        $statement->bind_param('s',$this->mobile);
        $statement->execute();
        $statement->bind_result(
            $user['blog'],
            $user['username'],
            $user['password']);
        $statement->fetch();
        $statement->close();
        $mysql->close();
        return $user;
    }

    private function updateUser()
    {
        $mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $query = 'update users set
            blog=?,
            username=?,
            password=?
            where mobile=?';
        $statement = $mysql->prepare($query);
        $statement->bind_param('ssss',
            $this->blog,
            $this->username,
            $this->password,
            $this->mobile);
        $statement->execute();
        $statement->close();
        $mysql->close();
    }

    public function setBlog($blog)
    {
        $this->blog = $blog;
        $this->updateUser();
        $status = 'The blog "'.$this->blog.'" has been registered successfully. Send #wordpress.set Username,Password to configure posting credentials.';
        return $status;
    }

    public function setUserPass($credentials)
    {
        if($this->validateUserPassFormat($credentials))
        {
            list($username, $password) = explode(',',$credentials);
            $this->username = $username;
            $this->password = $password;
            $this->updateUser();
            $status = 'Your username and password have been set. They must be valid for your posts to get published.';
        }
        else
        {
            $status = 'Invalid format for credentials. Send #worpress.set Username,Password';
        }
        return $status;
    }

    private function isBlogSet()
    {
        return (!empty($this->blog));
    }

    private function isUserPassSet()
    {
        return (!empty($this->username) && !empty($this->password));
    }

    private function validateUserPassFormat($credentials)
    {
        return preg_match('/^(\s*)(\w+)(\s*)(,)(\s*)(\w+)(\s*)/', $credentials);
    }

    public function newBlogPost($message)
    {
        if(!$this->isBlogSet())
        {
            $status = 'Welcome to WordPress on 55444.in. You have not registered a blog with this account yet. To register a blog, send #wordpress.register BlogName';
        }
        else if(!$this->isUserPassSet())
        {
            $status = 'The username and password combination is not set. To set, send #wordpress.set Username,Password';
        }
        else if(trim($message) == '')
        {
            $status = 'Welcome to WordPress on 55444.in. The blog "'.$this->blog.'" has been registered with your account. Send #wordpress Title > PostContents to publish your post.';
        }
        else
        {
            $credentials = array(
                'blog'=>$this->blog,
                'username'=>$this->username,
                'password'=>$this->password);
            $blogpost = new BlogPost($credentials, $message);
            if($blogpost->status)
            {
                $status = 'Your blog post has been successfully published.';
            }
            else
            {
                $status = 'Your post could not be published. Please send #wordpress Title > PostContents. Also make sure the registered blog and associated credentials are valid.';
            }
        }
        return $status;
    }

    public function appendPost($message)
    {
	#To Do
    }

    public function clearPost()
    {
	#To Do
    }

}
