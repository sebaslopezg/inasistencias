const displayNombre = document.querySelector('#displayNombre')
const displayApellido = document.querySelector('#displayApellido')
const displayDocumento = document.querySelector('#displayDocumento')
const displayGenero = document.querySelector('#displayGenero')
const displayTelefono = document.querySelector('#displayTelefono')
const displayEmail = document.querySelector('#displayEmail')
const displayCodigo = document.querySelector('#displayCodigo')
const displayRol = document.querySelector('#displayRol')
const displayFirma = document.querySelector('#displayFirma')

const userName = document.querySelector('#userName')
const userApellido = document.querySelector('#userApellido')
const userDocument = document.querySelector('#userDocument')
const userGenero = document.querySelector('#userGenero')
const userTelefono = document.querySelector('#userTelefono')
const userEmail = document.querySelector('#userEmail')
const userCodigo = document.querySelector('#userCodigo')
const userRol = document.querySelector('#userRol')

genero = {
    M:'Masculino',
    F:'Femenino',
    O:'Otro',
}

rol = {
    COORDINADOR:'Coordinador',
    APRENDIZ:'Aprendiz',
    INSTRUCTOR:'Instructor'
}

fetch(`${base_url}/usuarios/getUsariosById/${userId}`)
.then((response) => response.json())
.then((data) => {
    if (data.status) {
        data = data.data

            displayNombre.innerHTML = data.nombre
            displayApellido.innerHTML = data.apellido
            displayDocumento.innerHTML = data.documento
            displayGenero.innerHTML = genero[data.genero]
            displayTelefono.innerHTML = data.telefono
            displayEmail.innerHTML = data.correo
            displayCodigo.innerHTML = data.codigo
            displayRol.innerHTML = rol[data.rol]
            displayFirma.innerHTML = data.firma
            //editables
            userName.value = data.nombre
            userApellido.value = data.apellido
            userDocument.value = data.documento
            userGenero.value = data.genero
            userTelefono.value = data.telefono
            userEmail.value = data.correo
            userCodigo.value = data.codigo
            userRol.value = data.rol
    }else{
        Swal.fire({
            title: "Error",
            text: "No se pudieron cargar los datos del usuario: Error desconocido",
            icon: "error"
        });
    }
})
.catch((error) => {
    Swal.fire({
        title: "Error",
        text: `No se pudieron cargar los datos del usuario: ${error}`,
        icon: "error"
    });
})

document.addEventListener('submit', (e) => {
    e.preventDefault()
    const target = e.target
    const form = new FormData(e.target)

    if (target.dataset.form == 'editProfile') {
        fetch(`${base_url}/usuarios/editprofile/${userId}`,{
            method:'post',
            body:form
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status) {
                Swal.fire({
                    title: "Actualizar Usuario",
                    text: `Usuario actualizado exitosamente`,
                    icon: "success"
                })        
            }else{
                Swal.fire({
                    title: "Error",
                    text: data.msg,
                    icon: "error"
                })
            }

        })
        .catch((error) => {
            Swal.fire({
                title: "Error",
                text: `Error al intentar actualizar el usuario: ${error}`,
                icon: "error"
            })
        })
    }

    if (target.dataset.form == 'editPass') {
        fetch(`${base_url}/usuarios/updatepass/${userId}`,{
            method:'post',
            body:form
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status) {
                Swal.fire({
                    title: "Actualizar Contraseña",
                    text: `Contraseña actualizada correctamente`,
                    icon: "success"
                })        
            }else{
                Swal.fire({
                    title: "Error",
                    text: data.msg,
                    icon: "error"
                })
            }

        })
        .catch((error) => {
            Swal.fire({
                title: "Error",
                text: `Error al intentar actualizar el usuario: ${error}`,
                icon: "error"
            })
        })
    }
})