<?php
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
				}	public function getNom(){
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
				}	public function getNumber(){
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
				}