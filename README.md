# Bcrypt PHP Class #

This library is intended to work with PHP Password Hashing Functions 


Requirements
============

Requires `PHP >= 5.5`


Usage
=====

**Creating Password Hashes**

To create a password hash from a password:
````PHP
    $hash = Bcrypt::hash($password);
````

**Checking Password match Hashes**
````PHP
    $hash = Bcrypt::check($password, $hash);
````

## Options ##

Optionally you can add the *algorithm* to use, [the following algorithms are currently supported](http://php.net/manual/en/function.password-hash.php) (you must define one of this):
- PASSWORD_DEFAULT - Use the bcrypt algorithm (default as of PHP 5.5.0). Note that this constant is designed to change over time as new and stronger algorithms are added to PHP. For that reason, the length of the result from using this identifier can change over time. Therefore, it is recommended to store the result in a database column that can expand beyond 60 characters (255 characters would be a good choice).
- PASSWORD_BCRYPT - Use the CRYPT_BLOWFISH algorithm to create the hash. This will produce a standard crypt() compatible hash using the "$2y$" identifier. The result will always be a 60 character string, or FALSE on failure.

You can add the algorithmic *cost* parameter, 10 is a good baseline cost, but you may want to consider increasing it depending on your hardware. The cost can range from `4` to `31`. 

**Benchmark your server**
To determine how high of a cost you can afford try this:
````PHP
    $hash = Bcrypt::appropriateCost();
````

This function calcs how much cost support your server. You can add some options: 
- $timeTarget: max time execution of password_hash function
- $startCost: the initial value of the cost