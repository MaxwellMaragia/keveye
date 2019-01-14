<?php
/**
 * Created by PhpStorm.
 * User: maxipain
 * Date: 12/10/2017
 * Time: 1:17 PM
 */

class DATABASE{

    public $con;

    public function __construct()
    {

        $this->con=mysqli_connect("localhost","root","","keveye")or die(mysqli_error($this->con));

        if(!$this->con)
        {
            echo "failed to connect";
        }

    }

}

   $obj = new DATABASE();

