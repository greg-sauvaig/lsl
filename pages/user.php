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
				}	public function getLogin(){
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
				}	public function getPass(){
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
				}	public function getVerified(){
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
				}	public function getSession(){
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
				}	public function getDroit(){
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
				}	public function getTime(){
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
				}