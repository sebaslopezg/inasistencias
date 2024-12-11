const input = document.getElementById('excel')
const date = new Date()
const display = document.querySelector('#display')
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
                fntSearchFicha(rows)
                if (fntCheckStatus()) {
                    fntSearchHorarios(rows)
                    fntPrintHorario()
                    procesData(horario)
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
    //let horario = {}
    let mesEncontrado = null
    let lastNotNull
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
    rows.forEach((row, rowNumber, arrRowCell) => {

        row.forEach((cell, cellNumber, arrRow) =>{

            meses.forEach(mes =>{
                
                if (cell == mes) {
                    
                    meses.forEach(m =>{
                        if (lastNotNull == m) {
                            hold = true
                        }
                    })

                    if (hold) {
                        mesPlaceholder = mes
                    }else{
                        mesEncontrado = mes
                        contadorDia = 0
                        diaEncontrado = []
                    }
                    lastNotNull = mes
                }
            })

            if (mesEncontrado != null) {
                if (Number.isInteger(cell)) {
                    
                    if (contadorDia > 5) {
                        //setea las horas
                        horasEncontrada.push(cell)
                        contadorDiaHorario = 0
                    }else{
                        if (hold && cell == 1) {
                            mesEncontrado = mesPlaceholder
                            hold = false
                        }
                        //lee los dias validos
                        let celdaInferior = arrRowCell[rowNumber+1][cellNumber]
                        if (celdaInferior != null && celdaInferior != 'FESTIVO') {
                            diaEncontrado.push(cell)
                        }
                        contadorDia++
                    }
                    lastNotNull = cell
                }else if(cell != null && cell != 'FESTIVO' && !fntCompareDays(cell) && !fntCompareMounths(cell)){

                    fecha = diaEncontrado[contadorDiaHorario] + '/' + (meses.findIndex(m => m == mesEncontrado)+1) + '/' + date.getFullYear()
                    horaInicio = horasEncontrada[horasEncontrada.length-2]
                    horaFin = horasEncontrada[horasEncontrada.length-1]
                    contenido = cell

                    horario[contadorHorario] = {
                        fecha:fecha,
                        horaInicio:horaInicio,
                        horaFin:horaFin,
                        contenido:contenido,
                        coordenada:{
                            fila: rowNumber+1,
                            celda_index: cellNumber,
                            celda_letra: celdas[cellNumber]
                        }
                    }
                    contadorHorario++
                    contadorDiaHorario++
                    //console.log(' | en fila: ' + (rowNumber+1) + ' | Celda: ' + celdas[cellNumber])
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

    node = document.createTextNode('contenido')
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
            <td>${dataRow.contenido}</td>
            <td>${dataRow.coordenada.fila} | ${dataRow.coordenada.celda_letra}</td>
        </tr>
        `
        
    })
    display.appendChild(table)
}

function procesData(data){
    Object.entries(data).forEach(row =>{
        dataRow = row[1]
        console.log(dataRow)        
    })
}