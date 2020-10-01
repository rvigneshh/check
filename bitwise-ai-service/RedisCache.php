<?php
require ("vendor/autoload.php");
Predis\Autoloader::register();

class RedisCache {

	private $redis;

	function __construct()
	{
		$this->host = get_option('bitwise_redis_settings_host');
		$this->password = get_option('bitwise_redis_settings_password');
		$this->port = get_option('bitwise_redis_settings_port');

		$this->redis = new Predis\Client('tcp://'.$this->host.':'.$this->port,[ 'parameters' => ['password' => $this->password] ] );
	}

        /**
         * [isExists checks the values exists Redis Cache]
         * @param [type] $key   [variable name]
         */
        /*************************************************************************/
        //Added by Vignesh on Aug 07th 2020 - (Checking if data exists in IQ Redis)
        /*************************************************************************/
        public function isExists($key){
                return $this->redis->exists($key);
        }

	/**
	 * [setValue set the values to Redis Cache]
	 * @param [type] $key   [variable name]
	 * @param [type] $value [set the value]
	 */

	public function setValue($key, $value){

		 try{
		 	$value = gzcompress($value,9);
		 	$this->redis->set($key, $value);

		 }catch(Exception $e){
		 	echo "Couldn't set the values";
		 	die($e->getMessage());
		 }
	}


	/**
	 * [getValue get the values from Redis Cache]
	 * @param  [type] $key [variable name]
	 * @return [type]      [return the stored value]
	 */

	public function getValue($key){

		try{
		 	$value = $this->redis->get($key);
			if(!empty($value))
			{
		 	return gzuncompress($value);
			}

		 }catch(Exception $e){
		 	echo "Couldn't retreive the values";
		 	die($e->getMessage());
		 }
	}

	/**
	 * [incrementBy description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */

	public function incrementBy($key,$value){

		try{
		 	$value = $this->redis->incrby($key,$value);
		 	return $value;

		}catch(Exception $e){
			echo "Couldn't able to increment the given value";
		 	die($e->getMessage());
		}
	}

	/**
	 * [decrementBy description]
	 * @param  [type] $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function decrementBy($key,$value){

		try{
		 	$value = $this->redis->decrby($key,$value);
		 	return $value;

		}catch(Exception $e){
			echo "Couldn't able to decrement the given value";
		 	die($e->getMessage());
		}
	}

	/**
	 * [increment description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function increment($key){

		try{
		 	$value = $this->redis->incr($key);
		 	return $value;

		}catch(Exception $e){
			echo "Couldn't able to increment the given value";
		 	die($e->getMessage());
		}
	}

	/**
	 * [decrement description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function decrement($key){

		try{
		 	$value = $this->redis->decr($key);
		 	return $value;

		}catch(Exception $e){
			echo "Couldn't increment the given value";
		 	die($e->getMessage());
		}
	}

	/**
	 * [hashSet description]
	 * @param  [type] $key   [description]
	 * @param  [type] $label [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function hashSet($key, $label, $value){

		try{
		 	$value = $this->redis->hset($key,$label,$value);

		}catch(Exception $e){
			echo "Couldn't set the hashed value";
		 	die($e->getMessage());
		}
	}

	/**
	 * [delete description]
	 * @param  [type] $key   [description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function delete($key,$value){
		try{
		 	$value = $this->redis->del($key,$value);
		 	return $value;
		}catch(Exception $e){
			echo "Failed to delete the values";
		 	die($e->getMessage());
		}
	}

        /**
         * [isExists checks the values exists Redis Cache]
         * @param [type] $key   [variable name]
         */
        /*************************************************************************/
        //Added by Vignesh on Aug 07th 2020 - (Checking if data exists in IQ Redis)
        /*************************************************************************/
        public function isExists_iq($key){
		$this->redis->select(2);
                return $this->redis->exists($key);
        }

        /**
         * [getValue get the values from Redis Cache]
         * @param  [type] $key [variable name]
         * @return [type]      [return the stored value]
         */
        public function getValue_iq($key){

                try{
                	$this->redis->select(2);
                        $value = $this->redis->get($key);
                        if(!empty($value))
                        {
                        return gzuncompress($value);
                        }

                 }catch(Exception $e){
                        echo "Couldn't retreive the values";
                        die($e->getMessage());
                 }
        }

	/*
         * [setValue set the values to Redis Cache]
         * @param [type] $key   [variable name]
         * @param [type] $value [set the value]
         */

        public function setValue_iq($key, $value){

                 try{
                        $value = gzcompress($value,9);
                        $this->redis->select(2);
                        $this->redis->set($key, $value);

                 }catch(Exception $e){
                        echo "Couldn't set the values";
                        die($e->getMessage());
                 }
        }

}
