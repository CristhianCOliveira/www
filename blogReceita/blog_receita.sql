CREATE TABLE ingredientes(

	ID INT AUTO_INCREMENT PRIMARY KEY,
	receita_id INT,
	nome VARCHAR(150),
	
	FOREIGN KEY (receita_id) REFERENCES receitas(id)
	ON DELETE CASCADE 
	);