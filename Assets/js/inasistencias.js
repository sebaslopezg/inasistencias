const txtCodigo = document.getElementById('txtCodigo')
const frmInasistencias = document.getElementById('frmInasistencias')
let code = ''

frmInasistencias.addEventListener('submit', (e)=>{
    e.preventDefault()
    sentCode()
})

document.addEventListener('keydown', (e)=>{

    if (txtCodigo != document.activeElement) {
        if (e.key != 'Enter') {
            code += e.key
        }else{
            enterPress()
        }   
    }else{
        code = ''
    }
})

function enterPress(){
    txtCodigo.value = code
    code = ''
    sentCode()
}

function sentCode(){
    console.log(`codigo enviado ${txtCodigo.value}`)
    fetch(base_url + `/inasistencias/setInasistencia/${txtCodigo.value}`, {
        method:'GET',
    })
    .then((res) => res.json())
    .then((data) => {
        if (data.status) {
            setToast(true, data.msg)
        }else{
            setToast(false, data.msg)
        }
        
    })
    code = ''
}

const toastTrigger = document.getElementById('liveToastBtn')
const liveToast = document.getElementById('liveToast')


function setToast(status, text){
    try {
        liveToast.classList.remove('text-bg-primary')
        liveToast.classList.remove('text-bg-danger')
    } catch (error) {}

let toastText = document.getElementById('toast_body')
let color
status ? color = 'text-bg-success' : color = 'text-bg-danger'
liveToast.classList.add(color)
toastText.innerHTML = text
const toastBootstrap = bootstrap.Toast.getOrCreateInstance(liveToast)
toastBootstrap.show()
}


if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(liveToast)
  toastTrigger.addEventListener('click', () => {
    toastBootstrap.show()
  })
}