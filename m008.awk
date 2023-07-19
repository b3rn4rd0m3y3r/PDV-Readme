function random(a, b){
	c = b - a
	c = a+c*rand()
	return c
	}
BEGIN { 
	FS=";"
	i = 1
	}
{ 
	arr[i,1] = $1
	arr[i,2] = $2
	arr[i,3] = $3
	arr[i,4] = $4
	i++
	}
END {
	NoProds = i - 1
	NoVenda = 0
	for(mes=1;mes<=12;mes++){
		for(dia=1;dia<=28;dia++){
			NoVenda++
			IndProd = sprintf("%d",random(1,96))
			printf("%d|%d/%d|%d|%s|%s|%s|%s\n",NoVenda,dia,mes,IndProd,arr[IndProd,1],arr[IndProd,2],arr[IndProd,3],arr[IndProd,4])
			}
		}
	}