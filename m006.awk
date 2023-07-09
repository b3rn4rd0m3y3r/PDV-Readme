# type fornec2.csv | awk -f m006.awk > fornec3.csv
function cidade(){
	indice = sprintf("%d",rand()*6+1)
	return indice
	}
BEGIN { 
	FS=";"
	arr[1] = "SP;São José do Rio Preto"
	arr[2] = "SP;São Bernardo do Campo"
	arr[3] = "RJ;Campos"
	arr[4] = "RJ;Niteroi"
	arr[5] = "MG;Uberlândia"
	arr[6] = "MG;Belo Horizonte"
	}
{ 
	forn = $1
	printf("%s;xxx;%s\n", $1,arr[cidade()])
	}
END {}