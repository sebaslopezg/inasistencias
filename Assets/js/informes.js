let infoAprendiz = document.querySelector("#infoAprendiz");
let tableVisibility = document.querySelector("#tabla-informe");
let tablaAsistencias = document.querySelector("#tabla-asistencia");
let mostrarInfo = document.querySelector("#mostrar-info");
let mostrarBtn = document.querySelector("#mostrar-btn");
let btnCerrarModal = document.querySelector("#btnCerrarModal");
let btnAsistencia = document.querySelector("#btnAsistencia");
let btnInasistencia = document.querySelector("#btnInasistencia");
let btnLimpiar = document.querySelector("#btnLimpiar");
let lblTitulo = document.querySelector("#titulo");
let btnFecha = document.querySelector("#btnFecha");
let search_ficha = document.querySelector("#buscador");
let btnPdf = document.querySelector("#btnPdf");
let btnPdfM = document.querySelector("#btnPdfmodal");
let cardInforme = document.querySelector(".class-informes");
let cardAsistencias = document.querySelector("#cardAsistencias");
let informeAsistencia = document.querySelector("#informe-asistencia");
let fechaTr = document.querySelector("#fecha-tr");
let columAprendiz = document.querySelector("#colum-aprendiz");
let GB_codigoFicha = 0;
let GB_idFicha = 0; // GB : Variable "Global o Publica", esta varible podemos utilizarla en cualquier parte del Documento.
let GB_fechaFiltro = 0;
let GB_nombreFicha = "";

// -----------------------------------
//             BOTONES
// -----------------------------------

btnCerrarModal.addEventListener("click", () => {
  mostrarInfo.innerHTML = "";
});

btnPdf.addEventListener("click", () => {
  // validamos que la tabla tenga contenido
  let th = fechaTr.querySelectorAll("th");
  console.log(th.length);
  if (th.length > 3) {
    if ($(`#tabla-asistencia tbody tr`).length > 0) {
      btnPdf.style.display = "block";
      const tabla = document.querySelector("#tabla-asistencia");
      const trAprendiz = tabla.querySelectorAll("tbody tr");
      const thFecha = tabla.querySelectorAll("thead th");

      let InfoTabla = [];
      let fechas = [];
      for (let i = 5; i < thFecha.length; i++) {
        fechas.push(thFecha[i].innerText.trim());
      }

      trAprendiz.forEach((tr) => {
        let td = tr.querySelectorAll("td");
        let trInfoData = {
          aprendiz: td[1].innerText.trim(),
          asistencias: []
        };

        for (let i = 2; i < td.length; i++) {
          trInfoData.asistencias.push({
            fecha: fechas[i - 2],
            estado: td[i].innerText.trim()
          });
        }

        InfoTabla.push(trInfoData);
      });

      let formData = new FormData();
      formData.append("numero", GB_codigoFicha);
      formData.append("nombre", GB_nombreFicha);
      formData.append("infoGeneral", JSON.stringify(InfoTabla));

      const params = new URLSearchParams(formData).toString();
      fetch(base_url + `/informes/generarPdfAsi/?${params}`, {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded"
        },
        method: "GET"
      })
        .then((res) => {
          if (res) {
            return res.blob();
          } else {
            throw new Error("No se pudo encontrar el archivo");
          }
        })
        .then((blob) => {
          const link = document.createElement("a");
          const url = window.URL.createObjectURL(blob);
          link.href = url;
          link.download = "Asistencia.pdf";
          link.click();
          window.URL.revokeObjectURL(url);
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error"
          });
        });
    } else {
      console.log("no renderizo");
    }
  } else {
    Swal.fire({
      title: "¡ Accion no valida !",
      icon: "error",
      text: "Esta accion no esá permitida.",
      showConfirmButton: false,
      timer: 1700
    });
  }
});

btnAsistencia.addEventListener("click", () => {
  cardInforme.style.display = "none";
  cardAsistencias.style.display = "block";
  search_ficha.style.display = "none";
  informeAsistencia.style.display = "block";
  btnAsistencia.style.display = "none";
  btnInasistencia.style.display = "block";
  btnLimpiar.style.display = "none";
  lblTitulo.style.display = "none";
  btnPdf.style.display = "block";
});

btnFecha.addEventListener("click", function () {
  let fecha = document.getElementById("filtroFecha").value;
  if (fecha.length > 0) {
    renderTablaAsistencia(fecha);
  } else {
    Swal.fire({
      icon: "warning",
      title: "¡ Accion no valida !",
      text: "Seleccione una fecha valida, para obtener la informacion.  "
    });
  }
});

btnInasistencia.addEventListener("click", () => {
  // -----------------------------------
  //    DESHABILITAMOS LOS ELEMENTOS (BTN AND TABLE).
  //  -----------------------------------
  cardInforme.style.display = "block";
  cardAsistencias.style.display = "none";
  informeAsistencia.style.display = "none";
  search_ficha.style.display = "block";
  btnAsistencia.style.display = "block";
  btnInasistencia.style.display = "none";
  btnPdf.style.display = "none";
  btnLimpiar.style.display = "block";
  lblTitulo.style.display = "block";
  // -----------------------------------
  //    LIMPIAMOS LAS TABLAS
  //  -----------------------------------
  let i = 0;
  $(`#tabla-asistencia tr${i + 1}`).each(function () {
    $(`#aprendiz-tr`).remove();
    $("#colum-fecha").remove();
    $("#colum-info-ficha").remove();
  });
});

document.addEventListener("click", (e) => {
  try {
    let action = e.target.closest("button").getAttribute("data-action");
    let idAprendiz = e.target.closest("button").getAttribute("data-id");

    if (action === "info") {
      fetch(base_url + "/informes/getFaltas/" + idAprendiz)
        .then((res) => res.json())
        .then((data) => {
          let btnModalPdf = `
            <button type="button" style="float:right; margin-right:5px;" id="btnPDFmodal" data-action="pdf" data-id="${idAprendiz}" 
            class="btn btn-outline-danger rounded-pill mb-3"> <i class="bi bi-filetype-pdf" style="font-size:larger;">
            </i></button>
            `;
          mostrarBtn.innerHTML = btnModalPdf;

          data.forEach((data) => {
            let row = `
            <div class="row">
                     <div class="mb-1 col-6">
                         <label for="fecha" class="form-label"> <b>Nombre - Aprendiz</b> </label>
                         <div class="card">
                             <div class="card-body">
                                 <ul class="list-group mt-3 ">
                                     <li class="list-group-item" style="background-color:#e9ecef;"> ${data.nombre_completo}</li>
                                 </ul>
                             </div>
                         </div>
                     </div>
                     <div class="mb-2 col-6">
                         <label for="fecha" class="form-label"><b>Fecha - Inasistencia</b> </label>
                         <div class="card">
                             <ul class="list-group mt-3 ">
                                   <li class="list-group-item" style="background-color:#e9ecef;"> ${data.fecha}</li>
                             </ul>
                         </div>
                     </div>
                 </div>
           `;
            mostrarInfo.innerHTML += row;
          });
        });

      $("#modalInfo").modal("show");
    } else if (action === "pdf") {
      let idAprendiz = e.target.closest("button").getAttribute("data-id");
      fetch(base_url + "/informes/generarPdf/" + idAprendiz)
        .then((res) => {
          if (res) {
            // Si la respuesta es exitosa (archivo encontrado), procesar la descarga
            console.log(res);
            return res.blob(); // Convertimos la respuesta en un Blob (archivo)
          } else {
            throw new Error("No se pudo encontrar el archivo");
          }
        })
        .then((blob) => {
          // Crear un enlace temporal para forzar la descarga
          const link = document.createElement("a");
          const url = window.URL.createObjectURL(blob);
          link.href = url;
          link.download = "Informe de Inasistencias.pdf"; // Puedes asignar el nombre del archivo que deseas descargar
          link.click();
          window.URL.revokeObjectURL(url); // Limpiar la URL del objeto después de la descarga
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error"
          });
        });
    }
  } catch {}
});
function renderTablaAsistencia(fecha) {
  // -----------------------------------
  //   TRAEMOS LAS FECHAS DEL HORARIO DEL INSTRUCTOR
  // -----------------------------------
  let reqInfo = `${GB_codigoFicha},${fecha}`;
  GB_fechaFiltro = fecha; // capturamos la fecha seleccionada, para hacer el filtrado de la tabla asistencia.
  fetch(base_url + "/informes/getFechaInstructor/" + reqInfo)
    .then((res) => res.json())
    .then((fechas) => {
      fechaTr.innerHTML = "";
      if (fechaTr.length == 2) {
        fechas.forEach((fecha) => {
          const th = document.createElement("th");
          th.textContent = fecha.fechaInicio;
          th.setAttribute("scope", "col");
          th.style.textAlign = "center";
          th.id = "colum-fecha";
          fechaTr.appendChild(th);
        });
      }
    })
    .catch((error) => console.error("Error cargando fechas:", error));

  // -----------------------------------
  //  MOSTRAMOS LA CONTROL DE ASISTENCIAS DE LA FICHA
  // -----------------------------------

  const reqData = `${GB_idFicha},${fecha}`;
  const fragment = document.createDocumentFragment();

  Promise.all([fetch(base_url + "/informes/getAsistencia/" + reqData).then((res) => res.json())])
    .then(([asistencias]) => {
      // Limpiar tabla existente
      columAprendiz.innerHTML = "";
      const fechasColumnas = [];
      const datosOrganizados = asistencias.reduce((acc, asistencia) => {
        acc[asistencia.aprendiz] = {
          nombre: asistencia.aprendiz,
          asistencias: {}
        };
        return acc;
      }, {});

      asistencias.forEach((asistencia) => {
        asistencia.asistencias.forEach((itemAsistencia) => {
          datosOrganizados[asistencia.aprendiz].asistencias[itemAsistencia.fecha] =
            itemAsistencia.estado;
          if (!fechasColumnas.includes(itemAsistencia.fecha)) {
            fechasColumnas.push(itemAsistencia.fecha);
          }
        });
      });

      if (fechaTr.children.length === 2) {
        fechasColumnas.forEach((fecha) => {
          const th = document.createElement("th");
          th.textContent = fecha;
          th.setAttribute("scope", "col");
          th.style.textAlign = "center";
          th.id = "colum-fecha";
          fechaTr.appendChild(th);
        });
      }
      Object.values(datosOrganizados).forEach((aprendiz, index) => {
        const tr = document.createElement("tr");
        tr.id = `aprendiz-tr${index}`;
        tr.innerHTML = `
                      <td style="text-align: center;">${index + 1}</td>
                      <td style="text-align: center;">${aprendiz.nombre}</td>
                  `;

        fechasColumnas.forEach((fecha) => {
          const td = document.createElement("td");
          td.style.textAlign = "center";
          td.name = "col-fecha";
          const estado = aprendiz.asistencias[fecha] || "";
          const badge = document.createElement("span");

          if (estado) {
            badge.className =
              estado === "Asistio"
                ? "badge rounded-pill bg-success"
                : "badge rounded-pill bg-danger";
            badge.textContent = estado;
          }

          td.appendChild(badge);
          tr.appendChild(td);
        });

        fragment.appendChild(tr);
      });

      columAprendiz.appendChild(fragment);
    })
    .catch((error) => {
      console.error("Error:", error);
      columAprendiz.innerHTML = `
          <tr><td colspan="100%" class="text-center">Error al cargar los datos. Intente nuevamente.</td></tr>
      `;
    });
}
$(document).ready(function () {
  // -----------------------------------
  //    VERIFICAR Fichas DISPONIBLES
  // -----------------------------------
  let availableFichas = [];
  fetch(base_url + "/informes/getFichas")
    .then((res) => res.json())
    .then((data) => {
      data.forEach((data) => {
        let fila = {
          label: "" + data.nombre_ficha + " - " + data.numeroFicha,
          value: "" + data.id + "",
          numeroFicha: "" + data.numeroFicha + "",
          nombreFicha: "" + data.nombre_ficha + ""
        };
        availableFichas.push(fila);
      });
    });

  // -----------------------------------
  //    AUTOCOMPLETADO DE FICHAS
  // -----------------------------------

  $("#ficha").autocomplete({
    // Establece como fuente de sugerencias el array "availableFichas", que contiene productos disponibles
    source: availableFichas,
    // event: Representa el evento JavaScript que desencadena la función select.
    // ui: Es un objeto de jQuery UI que contiene información específica sobre el elemento seleccionado en la lista de autocompletado.
    // Define la función que se ejecuta al seleccionar un producto de la lista de autocompletado
    select: function (event, ui) {
      // Pasa el idFicha (ui.item.value), el nombre(ui.item.label), el numeroFicha (ui.item.precio), como argumentos.
      agregarFicha(ui.item.value, ui.item.label, ui.item.numeroFicha, ui.item.nombreFicha);
      // Limpia el campo de entrada después de que se haya seleccionado un Ficha
      $("#ficha").val("");
      return false;
    }
  });

  function agregarFicha(idFicha, label, numeroFicha, nombreFicha) {
    // Verificar que solo haya eligido 1 Ficha, para evitar Cruce de Informacion.
    if ($("#tabla-infoFicha tbody tr").length < 1) {
      tableVisibility.style.display = "block";
      btnAsistencia.style.display = "block";
      GB_nombreFicha = nombreFicha;
      GB_codigoFicha = numeroFicha;
      GB_idFicha = idFicha;
      // Verificar si la Ficha ya está en la tabla
      let fichaYaAgregado = false;
      // Itera sobre cada fila de la tabla para comprobar si el ID del ficha ya existe
      $("#tabla-infoFicha tbody tr").each(function () {
        if ($(this).find("[name='idFicha[]']").val() == idFicha) {
          // Si encuentra una coincidencia de ID, marca fichaYaAgregado como true
          fichaYaAgregado = true;
          // Sale del ciclo
          return false;
        }
      });

      // Si la ficha ya está en la tabla, muestra una alerta usando SweetAlert
      if (fichaYaAgregado) {
        Swal.fire({
          icon: "warning",
          title: "Ficha ya agregada ",
          text: "La Ficha ya está selecionada en la tabla de Fichas."
        });
      } else {
        // traemos los APRENDICES disponibles para asignarlos a la ficha Seleccionada.
        fetch(base_url + "/informes/getInfoAprendicesFicha/" + idFicha)
          .then((res) => res.json())
          .then((data) => {
            data.forEach((data) => {
              let texto = ` <tr id="aprendiz-tr">
              <td>${data.nombre_completo}</td><td>${data.correo}</td><td style="text-align: center;">${data.faltas}</td><td style="text-align: center;">${data.accion}</td>
              </tr>  `;
              infoAprendiz.innerHTML += texto;
            });
          });

        let tr = document.createElement("tr");
        let th1 = document.createElement("th");
        let th2 = document.createElement("th");
        let text1 = document.createTextNode(`${nombreFicha}`);
        let text2 = document.createTextNode(`${numeroFicha}`);

        th1.appendChild(text1);

        th1.setAttribute("style", "font-size: large; text-align: center;");
        th2.appendChild(text2);

        th2.setAttribute("style", "font-size: large; text-align: center;");
        tr.appendChild(th1);
        tr.setAttribute("id", "trInfoFicha");
        tr.appendChild(th2);
        $("#tabla-infoFicha tbody").append(tr);
      }
    } else {
      Swal.fire({
        icon: "warning",
        title: "¡ Ya hay una ficha selecionada !",
        text: "Elimina la ficha anterior, para eligir una nueva ficha."
      });
    }
  }
  $(document).on("click", ".eliminar-fila", function () {
    $(this).closest("tr").remove();

    // ------------------------------------------------
    //    DESHABILITAMOS LOS ELEMENTOS (BTN AND TABLE)
    //  -----------------------------------------------

    tableVisibility.style.display = "none";
    btnAsistencia.style.display = "none";
    btnAsistencia.style.display = "none";
    btnInasistencia.style.display = "none";
    btnPdf.style.display = "none";
    fechaTr.style.display = "none";
    // -----------------------------------
    //    LIMIPIAMOS LA TABLA INFORME
    // -----------------------------------
    $("#tabla-infoFicha tr").each(function () {
      $("#ficha-tr").remove();
      $("#trInfoFicha").remove();
    });

    $("#tabla-aprendices tr").each(function () {
      $("#instru-tr").remove();
      $(`#aprendiz-tr`).remove();
    });
    // -----------------------------------
    //    LIMIPIAMOS LA TABLA ASISTENCIA
    // -----------------------------------
    let i = 0;
    let indice = 0;
    $(`#tabla-asistencia tr${i + 1}`).each(function () {
      $(`#aprendiz-tr${indice + 1}`).remove();
      $("#colum-fecha").remove();
    });
    $(`#tabla-asistencia thead tr`).each(function () {
      $("#colum-info-ficha").remove();
    });
  });
});
