const input = document.getElementById('excel')
const alertZone = document.querySelector('#alertZone')
const btnGuardarHorario = document.querySelector('#btnGuardarHorario')
const display = document.querySelector('#display')

const date = new Date()
const meses = [
    'ENERO', 
    'FEBRERO', 
    'MARZO', 
    'ABRIL', 
    'MAYO', 
    'JUNIO', 
    'JULIO', 
    'AGOSTO', 
    'SEPTIEMBRE', 
    'OCTUBRE', 
    'NOVIEMBRE', 
    'DICIEMBRE'
]

const diasSemana = [
    'LUNES',
    'MARTES',
    'MIÉRCOLES',
    'JUEVES',
    'VIERNES',
    'SÁBADO'
]
let horario = {}

//array solo para testear
const celdas = [
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']

let fileStatus = {}
let ficha = null
let cantidadCeldasProcesadas
let cantidadDatosFormateados
let dataProcesada

btnGuardarHorario.addEventListener('click', ()=>{ 
    if (horario != null) {
        sentData()
    }
})

input.addEventListener('change', () => {
    fileStatus = {
        code: 0,
        msg: ""
    }

    if (input.files.length > 0) {
        let fileName = input.files[0].name;
        let fileExtention = fileName.split('.')
        fileExtention = fileExtention[fileExtention.length-1]

        if (fileExtention != 'xlsx') {
            fileStatus = {
                code: 1,
                msg: "El archivo no tiene el formato incorrecto, solo se permiten archivos .xlsx"
            }
            fntCheckStatus()
        }else{
            readXlsxFile(input.files[0]).then((rows) => {
                cantidadCeldasProcesadas = 0
                cantidadDatosFormateados = 0
                dataProcesada = null
                fntSearchFicha(rows)
                if (fntCheckStatus()) {
                    fntSearchHorarios(rows)
                    if (horario == null) {
                        Swal.fire({
                            title: "Error",
                            text: "No se lograron obtener datos",
                            icon: "error"
                        });
                    }else{
                        Swal.fire({
                            title: "Carga de datos",
                            text: "Se han cargado datos",
                            icon: "info"
                        });
                        dataProcesada = procesData(horario)
                        printProcesData()
                        printAlert()
                        enableButtons()
                    }
                }
            })
        }
    }
})

function fntCheckStatus(){
    let response
    if (fileStatus.code > 0) {
        Swal.fire({
            title: "Error",
            text: fileStatus.msg,
            icon: "error"
          });
        response = false
    }else{
        response = true
    }
    return response
}

function fntSearchFicha(rows){

    rows.forEach(row => {
        row.forEach((cell, index, arrRow) =>{
            if (cell == "Ficha") {
                ficha = arrRow[index+1]
            }
        })
    })

    if(ficha == null) {
        fileStatus = {
            code: 2,
            msg:"No se pudo encontrar el numero de ficha"
        }
    }
}

function fntSearchHorarios(rows){
    let mesEncontrado = null
    let contadorDia = 0
    let contadorDiaHorario = 0
    let mesPlaceholder
    let hold = false
    let diaEncontrado = []
    let horasEncontrada = []
    let fecha
    let horaInicio
    let horaFin
    let contenido
    let contadorHorario = 0
    let filaDelMes = 0
    cantidadCeldasProcesadas = rows.length

    rows.forEach((row, rowNumber, arrRowCell) => {

        row.forEach((cell, cellNumber, arrRow) =>{

            meses.forEach(mes =>{

                if (cell === mes) {
                    filaDelMes = rowNumber
                    contadorDia = 0
                    diaEncontrado = []
                }
                if (cell === mes || arrRowCell[filaDelMes][cellNumber] === mes) {
                    mesEncontrado = mes
                }
            })

            if (mesEncontrado != null) {
                if (Number.isInteger(cell)) {
                    
                    if (contadorDia > 5) {
                        horasEncontrada.push(cell)
                        contadorDiaHorario = 0
                    }else{
                        if (hold && cell == 1) {
                            mesEncontrado = mesPlaceholder
                            hold = false
                        }
                        //lee los dias validos
                        let celdaInferior
                        try{
                            celdaInferior = arrRowCell[rowNumber+1][cellNumber]
                        }catch{
                            celdaInferior = null
                        } 

                        if (celdaInferior != null && celdaInferior != 'FESTIVO') {
                            diaEncontrado.push(cell)
                        }
                        contadorDia++
                    }
                }else if(cell != null && cell != 'FESTIVO' && !fntCompareDays(cell) && !fntCompareMounths(cell)){

                    fecha = diaEncontrado[contadorDiaHorario] + '/' + (meses.findIndex(m => m == mesEncontrado)+1) + '/' + date.getFullYear()

                    horaInicio = horasEncontrada[horasEncontrada.length-2]
                    horaFin = horasEncontrada[horasEncontrada.length-1]
                    let contenidoFormateado = cell.replace(/\/n/gi, ",")
                    contenido = contenidoFormateado.split(/\r?\n/)

                    horario[contadorHorario] = {
                        fecha:fecha,
                        horaInicio:horaInicio,
                        horaFin:horaFin,
                        instructor:contenido[0],
                        coordenada:{
                            fila: rowNumber+1,
                            celda_index: cellNumber,
                            celda_letra: celdas[cellNumber]
                        }
                    }
                    contadorHorario++
                    contadorDiaHorario++
                }
            }

        })

    })
}

function fntCompareDays(data){
    respuesta = false
    diasSemana.forEach(dia =>{
        if (data == dia) {
            respuesta = true
        }
    })
    return respuesta
}

function fntCompareMounths(data){
    respuesta = false
    meses.forEach(mes =>{
        if (data == mes) {
            respuesta = true
        }
    })

    return respuesta
}

function printAlert(){
    let cantidadRegistros = 0
    Object.entries(horario).forEach(row => cantidadRegistros++)
    
    html = `
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        Cantidad de celdas procesadas: <b>${cantidadCeldasProcesadas}</b>
        | Cantidad de datos validados: <b>${cantidadRegistros}</b>
        | Datos organizados: <b>${cantidadDatosFormateados}</b>
    </div>
    `
    alertZone.innerHTML = html
}

function fntPrintHorario(){

    let table = document.createElement('table')
    let thead = document.createElement('thead')
    let tbody = document.createElement('tbody')
    let th = document.createElement('th')
    let tr = document.createElement('tr')

    table.classList.add('table')

    let node = document.createTextNode('Fecha')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)
    

    node = document.createTextNode('Hora inicio')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Hora fin')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Instructor')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    node = document.createTextNode('Coordenada')
    th = document.createElement('th')
    th.appendChild(node)
    tr.appendChild(th)

    thead.appendChild(tr)
    table.appendChild(thead)
    table.appendChild(tbody)

    //console.log(Object.entries(horario))

    Object.entries(horario).forEach(row =>{
        dataRow = row[1]

        tbody.innerHTML += `
        <tr>
            <td>${dataRow.fecha}</td>
            <td>${dataRow.horaInicio}</td>
            <td>${dataRow.horaFin}</td>
            <td>${dataRow.instructor}</td>
            <td>${dataRow.coordenada.fila} | ${dataRow.coordenada.celda_letra}</td>
        </tr>
        `
        
    })
    display.appendChild(table)
}

function sentData(){
    let frmData = new FormData()
    
    Object.entries(dataProcesada).forEach(row =>{
        dataRow = row[1]
        console.log(dataRow)
        frmData.append('fecha', dataRow.fecha)
        frmData.append('horaInicio', dataRow.horaInicio)
        frmData.append('horaFin', dataRow.horaFin)
        frmData.append('instructor', dataRow.instructor)
    })

    fetch(base_url + '/horario/setHorario',{
        method: "POST",
        body: frmData,
    })
    .then((res)=>console.log(res))

}

function enableButtons(){
    btnGuardarHorario.removeAttribute('disabled')
}

 function procesData(data){
    let respuestaRaw = {}
    let arrFechas = []
    let respuesta = {}
    Object.entries(data).forEach(row =>{
        dataRow = row[1]
        arrFechas.push(dataRow.fecha)       
    })
    let uniqArrFechas = [...new Set(arrFechas)]
    //FIX IT!!!!
    uniqArrFechas.forEach((fecha, fechaIndex) => {
        let contenido = {}
        let indexCout = 0
        Object.entries(data).forEach((row) =>{
            dataRow = row[1]

            if (fecha == dataRow.fecha) {
                contenido[indexCout] = {
                    horaInicio:dataRow.horaInicio,
                    horaFin:dataRow.horaFin,
                    instructor:dataRow.instructor
                }
                indexCout++
            }  
        })

        respuestaRaw[fecha] = contenido
    })

    let letResponseIndex = 0
    Object.entries(respuestaRaw).forEach((data) =>{
        
        //console.log(data)
        let resFecha = data[0]
        let resHoraInicio
        let resHoraFin
        let resInstructor
        let resContenido = data[1]
        
        if (resContenido[0].instructor == resContenido[5].instructor) {
            resHoraInicio = resContenido[0].horaInicio
            resHoraFin = resContenido[5].horaFin
            resInstructor = resContenido[0].instructor

            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio,
                horaFin:resHoraFin,
                instructor:resInstructor
            }
    
            letResponseIndex++
        }else{
            //primer bloque
            resHoraInicio = resContenido[0].horaInicio
            resHoraFin = resContenido[2].horaFin
            resInstructor = resContenido[0].instructor

            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio,
                horaFin:resHoraFin,
                instructor:resInstructor
            }
    
            letResponseIndex++
            
            //segundo bloque
            resHoraInicio_bloque2 = resContenido[3].horaInicio
            resHoraFin_bloque2 = resContenido[5].horaFin
            resInstructor_bloque2 = resContenido[3].instructor
            
            respuesta[letResponseIndex] = {
                fecha:resFecha,
                horaInicio:resHoraInicio_bloque2,
                horaFin:resHoraFin_bloque2,
                instructor:resInstructor_bloque2
            }
            letResponseIndex++
        }
    })

    return respuesta
} 

function printProcesData(){
    let html = ""
    Object.entries(dataProcesada).forEach(data => {
        data = data[1]
        html += `
            <tr>
                <td>${data.fecha}</td>
                <td>${data.horaInicio}</td>
                <td>${data.horaFin}</td>
                <td>${data.instructor}</td>
            </tr>
        `;
        cantidadDatosFormateados++
    })


    display.innerHTML = `
    
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora inicio</th>
                        <th>Hora fin</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody>
                    ${html}
                </tbody>
            </table>
        </div>
    </div>

    `
}

