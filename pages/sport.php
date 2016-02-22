<?phprequire 'getCo.php';class Sport{	private $_id;	private $_nom;	private $_number;	public function getId(){		return $this->_id	}	public function setId(){		$this->_id= $val;try{
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
				}	public function getNom(){		return $this->_nom	}	public function setNom(){		$this->_nom= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "nom" = $val");
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
				}	public function getNumber(){		return $this->_number	}	public function setNumber(){		$this->_number= $val;try{
					 $PDO=getCo();
					 $query = $PDO->prepare("UPDATE $value SET "number" = $val");
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