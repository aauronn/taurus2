<?php
	include "include/entorno.inc.php";
	include "include/funciones.inc.php";
	
	//***********************************************************
	// 		FILTRA REGISTROS PARA PERSONA FISICA
	//***********************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS"){		
		$fecha = $_POST["fecha"];
		$historico=$_POST["historico"];//$historico=1 significa que se va a sacar un reporte historico, $historico=0 un reporte al dia
		//'$fecha' = "2012-01-26";
		$GLOBALS["fecha_buro"] = $_POST["fecha"];
		$tipo_persona = $_POST["tipo_persona"]; 
		
		//**************************************************************
		//Esto va dentro de la condicion donde veo que clientes jalo
		//WHERE tipo_persona = $tipo_persona
		//**************************************************************
		
		$strsql="				(SELECT ventas.clientes.clave_cliente,    
        max(f_dias_vencimiento_buro_credito( datediff(day, ( ventas.enc_facturas.fecha +  ventas.enc_facturas.plazo) ,'$fecha') ) )as dias_vencimiento ,
        ";
		if ($historico==1) {
			$strsql.="f_buro_credito_saldo_vencido_historico('$fecha', ventas.clientes.clave_cliente, 1) as saldo_vencido2,
			 sum(round(f_conv_moneda((ventas.enc_facturas.saldo),ventas.enc_facturas.moneda, ventas.enc_facturas.tipo_cambio, 'MN'),2)) as saldo_acumulado,";
		}else {
			$strsql.="f_buro_credito_saldo_vencido('$fecha', ventas.clientes.clave_cliente, 1) as saldo_vencido2,
			sum(round(f_conv_moneda(ventas.enc_facturas.saldo,ventas.enc_facturas.moneda, ventas.enc_facturas.tipo_cambio, 'MN'),2)) as saldo_acumulado,";
		}
        $strsql.=" count(ventas.enc_facturas.num_docto) as num_docto,   
        UCASE( ventas.clientes.nombre) as nombre,   
        UCASE(ventas.clientes.direccion_1) as calle_numero,   
        UCASE(ventas.clientes.direccion_2) as colonia,   
        // UCASE(ventas.ciudades.cve_estado_sepomex) as cve_estado_sepomex,   
        // UCASE(ventas.ciudades.cve_pais_sepomex) as cve_pais_sepomex,   
        UCASE(ventas.clientes.rfc) as  rfc,
              ventas.clientes.cp as cp ,
        UCASE(ventas.ciudades.ciudad) as ciudad,
        UCASE( ventas.ciudades.estado) as estado
        ,(SELECT FIRST l.fecha FROM enc_facturas l WHERE l.clave_cliente=ventas.clientes.clave_cliente ORDER BY l.fecha ASC ) AS fecha_apertura 
        ,(SELECT FIRST y.fecha AS fecha_pago FROM enc_facturas x INNER JOIN pagos_cxc y
                ON x.num_docto=y.num_docto WHERE x.clave_cliente=ventas.clientes.clave_cliente AND x.clave_bodega=y.clave_bodega and y.fecha<'$fecha' ORDER BY fecha_pago DESC) AS fecha_ultimo_pago  
        //,(SELECT FIRST m.fecha FROM enc_facturas m WHERE m.clave_cliente=ventas.clientes.clave_cliente and m.fecha<'$fecha' ORDER BY m.fecha DESC) AS fecha_ultima_compra
        ,ventas.clientes.limite_credito
        ,ventas.clientes.plazo
        ,'Individual' as tipo_credito
        ,f_tipo_persona_buro (ventas.clientes.rfc) as tipo_persona_buro
    	FROM ventas.clientes, ventas.enc_facturas, ventas.ciudades  
   		WHERE ( ventas.enc_facturas.clave_cliente = ventas.clientes.clave_cliente ) 
   				and  f_tipo_persona_buro (ventas.clientes.rfc) = 1 //and //max!=0 and
         		and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
         		and ( ( ventas.enc_facturas.fecha < '$fecha' ) AND ";
       	if($historico==1){
   			$strsql.="( ventas.enc_facturas.num_docto IN 	
                            (select num_docto FROM enc_facturas where 1=1 
                                and clave_cliente IN (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona=1) 
                                and fecha <= '$fecha' 
                                and num_docto in (select num_docto from enc_facturas where saldo > 0)
                        	    --(select num_docto from pagos_cxc where fecha <= '$fecha') 	
                             //   and num_docto not in(select num_docto_factura from enc_notas_credito) // verificar si sale sobrando
              /*                  and (num_docto in(select num_docto_factura from enc_notas_credito where 1=1 // verificar ke sea and
                                                    and num_docto_factura=ventas.enc_facturas.num_docto // verificar si sobra
                                                    and ventas.enc_facturas.saldo!=subtotal + iva
                                                )
                                    and ventas.enc_facturas.saldo!=0
                                )
*/
                                //and num_docto not in(select docto_dev from enc_dev importe dev dif saldo factura)
                                and cancelada != 'S' and sub_total!=0 order by fecha asc 
                            )
                        )    )";
       	}else {
       		$strsql.="( ventas.enc_facturas.saldo > 0 ) )";
       	}
		$strsql.="and ventas.enc_facturas.clave_cliente IN (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona=1)
			GROUP BY ventas.clientes.clave_cliente,   
        	//ventas.enc_facturas.moneda,
        	ventas.ciudades.cve_estado_sepomex,   
        	ventas.ciudades.cve_pais_sepomex,   
        	ventas.clientes.nombre,   
         	ventas.clientes.direccion_1,   
        	ventas.clientes.direccion_2,   
        	ventas.clientes.rfc  
        	,ventas.clientes.cp 
        	,ventas.ciudades.ciudad,ventas.ciudades.estado
        	//,max
        	,ventas.clientes.limite_credito
        	,ventas.clientes.plazo
        	//,fecha_ultima_compra
        	,fecha_ultimo_pago
		ORDER BY ventas.clientes.clave_cliente ASC)//, max asc//, ventas.enc_facturas.moneda ASC)
		union
(SELECT ventas.clientes.clave_cliente,    
        0 as dias_vencimiento ,
        sum(round(0 + 0, 2) ) as saldo_vencido2,
	    sum(round(f_conv_moneda((ventas.enc_facturas.sub_total + ventas.enc_facturas.iva), ventas.enc_facturas.moneda, ventas.enc_facturas.tipo_cambio, 'MN'),2)) as saldo_acumulado, 
        count(ventas.enc_facturas.num_docto) as num_docto,   
        UCASE( ventas.clientes.nombre) as nombre,   
        UCASE(ventas.clientes.direccion_1) as calle_numero,   
        UCASE(ventas.clientes.direccion_2) as colonia,   
        // UCASE(ventas.ciudades.cve_estado_sepomex) as cve_estado_sepomex,   
        // UCASE(ventas.ciudades.cve_pais_sepomex) as cve_pais_sepomex,   
        UCASE(ventas.clientes.rfc) as  rfc,
              ventas.clientes.cp as cp ,
        UCASE(ventas.ciudades.ciudad) as ciudad,
        UCASE( ventas.ciudades.estado) as estado
        ,(SELECT FIRST l.fecha FROM enc_facturas l WHERE l.clave_cliente=ventas.clientes.clave_cliente ORDER BY l.fecha ASC ) AS fecha_apertura 
        ,(SELECT FIRST y.fecha AS fecha_pago FROM enc_facturas x INNER JOIN pagos_cxc y
                ON x.num_docto=y.num_docto WHERE x.clave_cliente=ventas.clientes.clave_cliente AND x.clave_bodega=y.clave_bodega and y.fecha<'$fecha' ORDER BY fecha_pago DESC) AS fecha_ultimo_pago  
        //,(SELECT FIRST m.fecha FROM enc_facturas m WHERE m.clave_cliente=ventas.clientes.clave_cliente and m.fecha<'$fecha' ORDER BY m.fecha DESC) AS fecha_ultima_compra
        ,ventas.clientes.limite_credito
        ,ventas.clientes.plazo
        ,'Individual' as tipo_credito
        ,f_tipo_persona_buro (ventas.clientes.rfc) as tipo_persona_buro
    	FROM ventas.clientes, ventas.enc_facturas, ventas.ciudades  
   		WHERE ( ventas.enc_facturas.clave_cliente = ventas.clientes.clave_cliente ) 
   				and  f_tipo_persona_buro (ventas.clientes.rfc) = 1 //and //max!=0 and
         		and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
         		and ( ( ventas.enc_facturas.fecha <= '$fecha' ) AND  ( ventas.enc_facturas.num_docto IN 	
                            (select num_docto FROM enc_facturas where 1=1 
                                and clave_cliente not IN (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona=1)
                                and ventas.enc_facturas.clave_cliente not IN ('S601','S308','A014','S965','S985','S986','S987','S988','S989','S994','S995','S996','S997','S998','S999')
                                and fecha <= '$fecha' 
                                and num_docto in (select num_docto from enc_facturas where saldo != 0)
                        	    --(select num_docto from pagos_cxc where fecha <= '$fecha') 	
                                //and num_docto not in(select num_docto_factura from enc_notas_credito)
                              //  or (num_docto in(select num_docto_factura from enc_notas_credito where 1=1 
                              //                      and num_docto_factura=ventas.enc_facturas.num_docto
                              //                      and ventas.enc_facturas.saldo!=subtotal + iva
                              //                  )
                              //      and ventas.enc_facturas.saldo!=0
                              //  )
                                //and num_docto not in(select docto_dev from enc_dev importe dev dif saldo factura)
                                and cancelada != 'S' and sub_total!=0 order by fecha asc 
                            )
                        )  )   
            and ventas.enc_facturas.clave_cliente not IN (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona=1)
            and ventas.enc_facturas.clave_cliente not IN ('S601','S308','A014','S965','S985','S986','S987','S988','S989','S994','S995','S996','S997','S998','S999')
			GROUP BY ventas.clientes.clave_cliente,   
        	//ventas.enc_facturas.moneda,
        	ventas.ciudades.cve_estado_sepomex,   
        	ventas.ciudades.cve_pais_sepomex,   
        	ventas.clientes.nombre,   
         	ventas.clientes.direccion_1,   
        	ventas.clientes.direccion_2,   
        	ventas.clientes.rfc  
        	,ventas.clientes.cp 
        	,ventas.ciudades.ciudad,ventas.ciudades.estado
        	//,max
        	,ventas.clientes.limite_credito
        	,ventas.clientes.plazo
        	//,fecha_ultima_compra
        	,fecha_ultimo_pago
		ORDER BY ventas.clientes.clave_cliente ASC)//, max asc//, ventas.enc_facturas.moneda ASC)
";
		$strsql3="select * from((select clave_cliente, 
        max(dias_vencimiento) as dias_vencimiento, 
        (select DISTINCT saldo_vencido3) as saldo_vencido2,
        sum(saldo_acumulado3) as saldo_acumulado,
        count(num_docto) as num_docto,
        nombre,
        calle_numero,
        colonia,
        rfc,
        cp,
        ciudad,
        estado,
        fecha_apertura,
        fecha_ultimo_pago,
        limite_credito,
        plazo,
        tipo_credito,
        tipo_persona_buro
        from
        (SELECT ventas.clientes.clave_cliente,    
        max(f_dias_vencimiento_buro_credito( datediff(day, ( z.fecha +  z.plazo) ,'$fecha') ) )as dias_vencimiento ,
        f_buro_credito_saldo_vencido_historico('$fecha', ventas.clientes.clave_cliente, 1) as saldo_vencido3,
			 //sum(round(f_conv_moneda((z.saldo),z.moneda, z.tipo_cambio, 'MN'),2)) as saldo_acumulado, 
            //(sum(z.sub_total+z.iva)      
            (sum(round(f_conv_moneda((z.sub_total+z.iva),z.moneda, z.tipo_cambio, 'MN'),2))
			-     
			(case when(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)is null 
				then 0 
            else(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)end)
			)as saldo_acumulado3,

        z.num_docto as num_docto,   
        UCASE( ventas.clientes.nombre) as nombre,   
        UCASE(ventas.clientes.direccion_1) as calle_numero,   
        UCASE(ventas.clientes.direccion_2) as colonia,   
        // UCASE(ventas.ciudades.cve_estado_sepomex) as cve_estado_sepomex,   
        // UCASE(ventas.ciudades.cve_pais_sepomex) as cve_pais_sepomex,   
        UCASE(ventas.clientes.rfc) as  rfc,
              ventas.clientes.cp as cp ,
        UCASE(ventas.ciudades.ciudad) as ciudad,
        UCASE( ventas.ciudades.estado) as estado
        ,(SELECT FIRST l.fecha FROM enc_facturas l WHERE l.clave_cliente=ventas.clientes.clave_cliente ORDER BY l.fecha ASC ) AS fecha_apertura 
        ,(SELECT FIRST y.fecha AS fecha_pago FROM enc_facturas x INNER JOIN pagos_cxc y
                ON x.num_docto=y.num_docto WHERE x.clave_cliente=ventas.clientes.clave_cliente AND x.clave_bodega=y.clave_bodega and y.fecha<'$fecha' ORDER BY fecha_pago DESC) AS fecha_ultimo_pago  
        //,(SELECT FIRST m.fecha FROM enc_facturas m WHERE m.clave_cliente=ventas.clientes.clave_cliente and m.fecha<'$fecha' ORDER BY m.fecha DESC) AS fecha_ultima_compra
        ,ventas.clientes.limite_credito
        ,ventas.clientes.plazo
        ,'Individual' as tipo_credito
        ,f_tipo_persona_buro (ventas.clientes.rfc) as tipo_persona_buro
    	FROM ventas.clientes
		left join  ventas.enc_facturas z on z.clave_cliente=ventas.clientes.clave_cliente
		left join  ventas.ciudades      on ventas.ciudades.clave_ciudad=ventas.clientes.clave_ciudad
   		WHERE ( z.clave_cliente = ventas.clientes.clave_cliente ) 
            and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
            and (   ( z.fecha <= '$fecha' )         
                          and   (z.num_docto not IN (select num_docto from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='".substr($fecha,0,7)."' ))
//                        AND     ( z.sub_total+z.iva!(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')) 
                  //      AND     ( z.sub_total+z.iva!=0) 
                        //AND (z.saldo != 0)
                        //AND     ( z.sub_total+z.iva!=0)
                       // AND (select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega) is not null
                        //or (z.saldo != 0 and( z.fecha<='$fecha'))
                )
            and z.clave_cliente in (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona =1)
			GROUP BY ventas.clientes.clave_cliente,   
        	ventas.ciudades.cve_estado_sepomex,   
        	ventas.ciudades.cve_pais_sepomex,   
        	ventas.clientes.nombre,   
         	ventas.clientes.direccion_1,   
        	ventas.clientes.direccion_2,   
        	ventas.clientes.rfc  
        	,ventas.clientes.cp 
        	,ventas.ciudades.ciudad,ventas.ciudades.estado
        	,ventas.clientes.limite_credito
        	,ventas.clientes.plazo
        	,fecha_ultimo_pago
            ,clave_bodega
            ,num_docto
		ORDER BY ventas.clientes.clave_cliente ASC
        ) as tt
        where saldo_acumulado3>0
        GROUP BY      clave_cliente,
                    //dias_vencimiento,
                    nombre,
                    calle_numero,
                    colonia,
                    rfc,
                    cp,
                    ciudad,
                    estado,
                    saldo_vencido3,
                    fecha_apertura,
                    fecha_ultimo_pago,
                    limite_credito,
                    tipo_credito,plazo,
                    tipo_persona_buro
        ORDER BY clave_cliente
        )
UNION
(select clave_cliente, 
        max(dias_vencimiento) as dias_vencimiento, 
        (select DISTINCT saldo_vencido3) as saldo_vencido2,
        sum(saldo_acumulado3) as saldo_acumulado,
        count(num_docto) as num_docto,
        nombre,
        calle_numero,
        colonia,
        rfc,
        cp,
        ciudad,
        estado,
        fecha_apertura,
        fecha_ultimo_pago,
        limite_credito,
        plazo,
        tipo_credito,
        tipo_persona_buro
        from
        (SELECT ventas.clientes.clave_cliente,    
        0 as dias_vencimiento ,
        0 as saldo_vencido3,
			 //sum(round(f_conv_moneda((z.saldo),z.moneda, z.tipo_cambio, 'MN'),2)) as saldo_acumulado, 
            //(sum(z.sub_total+z.iva)      
            (sum(round(f_conv_moneda((z.sub_total+z.iva),z.moneda, z.tipo_cambio, 'MN'),2))
			-     
			(case when(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)is null 
				then 0 
            else(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)end)
			)as saldo_acumulado3,

        z.num_docto as num_docto,   
        UCASE( ventas.clientes.nombre) as nombre,   
        UCASE(ventas.clientes.direccion_1) as calle_numero,   
        UCASE(ventas.clientes.direccion_2) as colonia,   
        // UCASE(ventas.ciudades.cve_estado_sepomex) as cve_estado_sepomex,   
        // UCASE(ventas.ciudades.cve_pais_sepomex) as cve_pais_sepomex,   
        UCASE(ventas.clientes.rfc) as  rfc,
              ventas.clientes.cp as cp ,
        UCASE(ventas.ciudades.ciudad) as ciudad,
        UCASE( ventas.ciudades.estado) as estado
        ,(SELECT FIRST l.fecha FROM enc_facturas l WHERE l.clave_cliente=ventas.clientes.clave_cliente ORDER BY l.fecha ASC ) AS fecha_apertura 
        ,(SELECT FIRST y.fecha AS fecha_pago FROM enc_facturas x INNER JOIN pagos_cxc y
                ON x.num_docto=y.num_docto WHERE x.clave_cliente=ventas.clientes.clave_cliente AND x.clave_bodega=y.clave_bodega and y.fecha<'$fecha' ORDER BY fecha_pago DESC) AS fecha_ultimo_pago  
        //,(SELECT FIRST m.fecha FROM enc_facturas m WHERE m.clave_cliente=ventas.clientes.clave_cliente and m.fecha<'$fecha' ORDER BY m.fecha DESC) AS fecha_ultima_compra
        ,ventas.clientes.limite_credito
        ,ventas.clientes.plazo
        ,'Individual' as tipo_credito
        ,f_tipo_persona_buro (ventas.clientes.rfc) as tipo_persona_buro
    	FROM ventas.clientes
		left join  ventas.enc_facturas z on z.clave_cliente=ventas.clientes.clave_cliente
		left join  ventas.ciudades      on ventas.ciudades.clave_ciudad=ventas.clientes.clave_ciudad
   		WHERE ( z.clave_cliente = ventas.clientes.clave_cliente ) 
            and (f_tipo_persona_buro(z.rfc) = 1) 
            and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
            and (   ( z.fecha <= '$fecha' )         
                          and   (z.num_docto not IN (select num_docto from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='".substr($fecha,0,7)."' ))
//                        AND     ( z.sub_total+z.iva!(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')) 
                  //      AND     ( z.sub_total+z.iva!=0) 
                        and (clientes.status_credito='O')
                        //AND     ( z.sub_total+z.iva!=0)
                       // AND (select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega) is not null
                        //or (z.saldo != 0 and( z.fecha<='$fecha'))
                        
                )
            and z.clave_cliente not in (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona =1)
            and z.clave_cliente not in ('S601','S308','A014','S965','S985','S986','S987','S988','S989','S994','S995','S996','S997','S998','S999')
			GROUP BY ventas.clientes.clave_cliente,   
        	ventas.ciudades.cve_estado_sepomex,   
        	ventas.ciudades.cve_pais_sepomex,   
        	ventas.clientes.nombre,   
         	ventas.clientes.direccion_1,   
        	ventas.clientes.direccion_2,   
        	ventas.clientes.rfc  
        	,ventas.clientes.cp 
        	,ventas.ciudades.ciudad,ventas.ciudades.estado
        	,ventas.clientes.limite_credito
        	,ventas.clientes.plazo
        	,fecha_ultimo_pago
            ,clave_bodega
            ,num_docto
		ORDER BY ventas.clientes.clave_cliente ASC
        ) as tt
        where saldo_acumulado3>0
        GROUP BY      clave_cliente,
                    //dias_vencimiento,
                    nombre,
                    calle_numero,
                    colonia,
                    rfc,
                    cp,
                    ciudad,
                    estado,
                    saldo_vencido3,
                    fecha_apertura,
                    fecha_ultimo_pago,
                    limite_credito,plazo,
                    tipo_credito,
                    tipo_persona_buro
        ORDER BY clave_cliente
        )) as tt
order by clave_cliente";
		hacerArchivo($strsql);
		paginacion_buscar($db,$strsql3, array("nombre","clave_cliente"),"clave_cliente",false,true,2);		
	}
	
	//********************************************************
	//		FILTRA REGISTROS PARA PERSONA MORAL
	//********************************************************
	if($_POST["opcion"]=="FILTRA_REGISTROS_PM"){		
		$fecha = $_POST["fecha"];
		$GLOBALS["fecha_buro"] = $_POST["fecha"];
		$tipo_persona = $_POST["tipo_persona"];
		$historico=$_POST["historico"];
			$strsql="					(select clave_cliente,
        dias_vencimiento,
        moneda,
        sum(saldo_acumulado2) as saldo_acumulado, 
        count(num_docto) as num_docto,
        nombre,
		calle_numero,
		colonia,
		rfc,
		cp
from	
(SELECT ventas.clientes.clave_cliente as clave_cliente,    
			f_dias_vencimiento_buro_credito( datediff(day, ( z.fecha +  z.plazo) ,'$fecha') ) as dias_vencimiento ,
			z.moneda,   
			//sum(z.saldo) as saldo_acumulado ,   
			(sum(z.sub_total+z.iva)  
			-     
			(case when(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)is null 
				then 0 
            else(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)end)
			)as saldo_acumulado2,
        //count(z.num_docto),   
				  z.num_docto as num_docto,
			UCASE(ventas.clientes.nombre) as nombre,   
			UCASE(ventas.clientes.direccion_1) as calle_numero,   
			UCASE(ventas.clientes.direccion_2) as colonia,   
			UCASE(ventas.clientes.rfc) as rfc,
				  ventas.clientes.cp as cp ,
			UCASE(ventas.ciudades.ciudad) as ciudad,
			UCASE( ventas.ciudades.estado) as estado
		FROM ventas.clientes
		left join  ventas.enc_facturas z on z.clave_cliente=ventas.clientes.clave_cliente
		left join  ventas.ciudades      on ventas.ciudades.clave_ciudad=ventas.clientes.clave_ciudad
        //left join  ventas.pagos_cxc     on ventas.pagos_cxc.num_docto = z.num_docto
		WHERE ( z.clave_cliente = ventas.clientes.clave_cliente ) 
            and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
            and (   ( z.fecha <= '$fecha' )         
                          and   (z.num_docto not IN (select num_docto from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha' and (select sum(sub_total+iva) as total from enc_facturas where clave_bodega=z.clave_bodega    and num_docto = z.num_docto)<(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')))
//                        AND     ( z.sub_total+z.iva!(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')) 
                  //      AND     ( z.sub_total+z.iva!=0) 
                      //  AND (z.saldo != 0)
                        //AND     ( z.sub_total+z.iva!=0)
                       // AND (select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega) is not null
                        or (z.saldo != 0 and( z.fecha<='$fecha'))
                )
            and z.clave_cliente in (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona =2)
            //and saldo_acumulado!=0
		GROUP BY 	ventas.clientes.clave_cliente,   
					z.moneda,   
                    dias_vencimiento,
					ventas.ciudades.cve_estado_sepomex,   
					ventas.ciudades.cve_pais_sepomex,   
					ventas.clientes.nombre,   
					ventas.clientes.direccion_1,   
					ventas.clientes.direccion_2,   
					ventas.clientes.rfc  , 
					ventas.clientes.cp ,
					ventas.ciudades.ciudad,
					ventas.ciudades.estado,
					z.num_docto,
					z.clave_bodega
		ORDER BY ventas.clientes.clave_cliente ASC, z.moneda ASC, dias_vencimiento ASC  ) as tt
where saldo_acumulado2>0 
group by clave_cliente, dias_vencimiento, moneda, nombre, calle_numero, colonia, rfc, cp 
order by clave_cliente asc, dias_vencimiento asc, moneda desc)
UNION
(select clave_cliente,
        dias_vencimiento,
        moneda,
        sum(saldo_acumulado2) as saldo_acumulado, 
        count(num_docto) as num_docto,
        nombre,
		calle_numero,
		colonia,
		rfc,
		cp
from	
(SELECT ventas.clientes.clave_cliente as clave_cliente,    
			0 as dias_vencimiento ,
			z.moneda,   
			//sum(z.saldo) as saldo_acumulado ,   
			(sum(z.sub_total+z.iva)  
			-     
			(case when(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)is null 
				then 0 
            else(select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega)end)
			)as saldo_acumulado2,
        //count(z.num_docto),   
				  z.num_docto as num_docto,
			UCASE(ventas.clientes.nombre) as nombre,   
			UCASE(ventas.clientes.direccion_1) as calle_numero,   
			UCASE(ventas.clientes.direccion_2) as colonia,   
			UCASE(ventas.clientes.rfc) as rfc,
				  ventas.clientes.cp as cp ,
			UCASE(ventas.ciudades.ciudad) as ciudad,
			UCASE( ventas.ciudades.estado) as estado
		FROM ventas.clientes
		left join  ventas.enc_facturas z on z.clave_cliente=ventas.clientes.clave_cliente
		left join  ventas.ciudades      on ventas.ciudades.clave_ciudad=ventas.clientes.clave_ciudad
        //left join  ventas.pagos_cxc     on ventas.pagos_cxc.num_docto = z.num_docto
		WHERE ( z.clave_cliente = ventas.clientes.clave_cliente ) 
            and (f_tipo_persona_buro(z.rfc) = 2) 
            and ( ventas.clientes.clave_ciudad = ventas.ciudades.clave_ciudad ) 
            and (   ( z.fecha <= '$fecha' )         
                          and   (z.num_docto not IN (select num_docto from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha' and (select sum(sub_total+iva) as total from enc_facturas where clave_bodega=z.clave_bodega    and num_docto = z.num_docto)<(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')))
//                        AND     ( z.sub_total+z.iva!(select sum(importe) from pagos_cxc where clave_bodega=z.clave_bodega and num_docto = z.num_docto and pagos_cxc.fecha<='$fecha')) 
                  //      AND     ( z.sub_total+z.iva!=0) 
                      //  AND (z.saldo != 0)
                        //AND     ( z.sub_total+z.iva!=0)
                       // AND (select sum(pagos_cxc.importe+pagos_cxc.iva) as total from pagos_cxc where fecha<='$fecha' and num_docto =z.num_docto and clave_bodega=z.clave_bodega) is not null
                        or (z.saldo != 0 and( z.fecha<='$fecha'))
                )
            and z.clave_cliente not in (SELECT clave_cliente FROM temp_clientes_buro where tipo_persona =2)
            and z.clave_cliente not in ('S601','S308','A014','S965','S985','S986','S987','S988','S989','S994','S995','S996','S997','S998','S999')
		GROUP BY 	ventas.clientes.clave_cliente,   
					z.moneda,   
					ventas.ciudades.cve_estado_sepomex,   
					ventas.ciudades.cve_pais_sepomex,   
					ventas.clientes.nombre,   
					ventas.clientes.direccion_1,   
					ventas.clientes.direccion_2,   
					ventas.clientes.rfc  , 
					ventas.clientes.cp ,
					ventas.ciudades.ciudad,
					ventas.ciudades.estado,
					z.num_docto,
					z.clave_bodega
		ORDER BY ventas.clientes.clave_cliente ASC, z.moneda ASC, dias_vencimiento ASC  ) as tt
where saldo_acumulado2>0 
group by clave_cliente, dias_vencimiento, moneda, nombre, calle_numero, colonia, rfc, cp 
order by clave_cliente asc, dias_vencimiento asc, moneda desc)
";
		hacerArchivo($strsql);
		paginacion_buscar($db,$strsql, array("nombre","clave_cliente"),"clave_cliente, dias_vencimiento",false,true,2);		
	}
	
	//*******************************************************
	//				BORRAR CLIENTE
	//*******************************************************
	
	if($_POST["opcion"]=="BORRAR_REGISTROS"){	
		paginacion_borrar($db,"DELETE FROM clientes WHERE clave_cliente='[id]'",false);	
	}

	
?>