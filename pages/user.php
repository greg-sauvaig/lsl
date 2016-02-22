<?phprequire 'getCo.php';class User{	private $_id;	private $_login;	private $_pass;	private $_verified;	private $_session;	private $_droit;	private $_time;	public function getId(){		return $this->_id	}	public function setId(){		$this->_id= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "id" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getLogin(){		return $this->_login	}	public function setLogin(){		$this->_login= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "login" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getPass(){		return $this->_pass	}	public function setPass(){		$this->_pass= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "pass" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getVerified(){		return $this->_verified	}	public function setVerified(){		$this->_verified= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "verified" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getSession(){		return $this->_session	}	public function setSession(){		$this->_session= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "session" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getDroit(){		return $this->_droit	}	public function setDroit(){		$this->_droit= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "droit" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}	public function getTime(){		return $this->_time	}	public function setTime(){		$this->_time= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "time" = $val");
					 $query->execute();
					 $cpt = $query->rowcount();
					 if ($cpt === 1){
						\r\t return $cpt;
					}
					
					 else{
						 return 0;
					
					}

				 } catch (Exception $e) {
					//to do log error
				}}