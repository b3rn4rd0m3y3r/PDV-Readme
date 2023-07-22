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
	ANO = 2023
	for(mes=1;mes<=12;mes++){
		for(dia=1;dia<=28;dia++){
			# Faz a data/hora do dia
			sdate = sprintf("%4s %2s %2s 0 0 0", ANO, mes, dia)
			mydate = mktime(sdate)
			# Dia da semana
			wkday = strftime("%a", mydate)
			# Sorteia o número de vendas que teremos neste dia
			VendasDia = random(2,6)
			for(vnd=1;vnd<=VendasDia;vnd++){
				NoVenda++
				# Sorteia a quantidade deste produto que será vendida
				Quant = sprintf("%d",random(1,3))
				# Sorteia o produto que será vendido
				IndProd = sprintf("%d",random(1,96))
				printf("%d|%2.0f/%2.0f|%d|%s|%s|%s|%d|%s|%s|%d|%s\n",NoVenda,dia,mes,IndProd,arr[IndProd,1],arr[IndProd,2],arr[IndProd,3],Quant,arr[IndProd,4], sdate, mydate, wkday)
				}
			}
		}
	}