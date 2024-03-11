$(document).ready(function () {
  maintableList();
});

function maintable_Row(jobs) {
  return `
        <tr>
          <td data-cell="JobID">${jobs.job_id}</td>
          <td data-cell="Date">${jobs.jobs_date}</td>
          <td data-cell="Flight" contenteditable="true" onblur= "convertToUppercase(this); editFlightData(this, ${jobs.flight_id}, 'flight_no')" onkeypress="checkEnterKey(event, this)">${jobs.flight_no}</td>
          <td data-cell="A/C Reg." contenteditable="true" onblur= "convertToUppercase(this); editFlightData(this, ${jobs.flight_id}, 'register')" onkeypress="checkEnterKey(event, this)">${jobs.register}</td>
          <td data-cell="BAY" contenteditable="true" onblur= "convertToUppercase(this); editFlightData(this, ${jobs.flight_id}, 'bay')" onkeypress="checkEnterKey(event, this)">${jobs.bay}</td>
          <td data-cell="Require">${jobs.equipment_type}</td>
          <td data-cell="Equ No." contenteditable="true" onblur= "convertToUppercase(this); editJobData(this, ${jobs.job_id}, 'equipment_no')" onkeypress="checkEnterKey(event, this)">
            ${jobs.equipment_no}
          </td>
          <td data-cell="เริ่ม" ${jobs.t_start_time ? 'contenteditable="true"' : ''} onblur="editTime(this, ${jobs.job_id}, 'start_time')" onkeypress="checkEnterKey(event, this)">
            ${jobs.t_start_time ? jobs.t_start_time : `<button class="start-btn" onclick="startJob(${jobs.job_id})">START</button>`}
          </td>
          <td data-cell="เลิกใช้" ${jobs.t_stop_time ? 'contenteditable="true"' : ''} onblur="editTime(this, ${jobs.job_id}, 'stop_time')" onkeypress="checkEnterKey(event, this)">
            ${jobs.t_stop_time ? jobs.t_stop_time : `<button class="stop-btn" onclick="stopJob(${jobs.job_id})"${!jobs.t_start_time ? 'disabled' : ''}>STOP</button>`}
          </td>
          <td data-cell="ผู้ปฏิบัติงาน 1" contenteditable="true" onblur= "convertToUppercase(this); editJobData(this, ${jobs.job_id}, 'username_1')" onkeypress="checkEnterKey(event, this)">${jobs.username_1}</td>
          <td data-cell="ผู้ปฏิบัติงาน 2" contenteditable="true" onblur= "convertToUppercase(this); editJobData(this, ${jobs.job_id}, 'username_2')" onkeypress="checkEnterKey(event, this)">${jobs.username_2}</td>
          <td data-cell="หมายเหตุ" contenteditable="true" onblur= "convertToUppercase(this); editJobData(this, ${jobs.job_id}, 'note')" onkeypress="checkEnterKey(event, this)">${jobs.note}</td>
          <td data-cell="ส่งงาน">
            ${(!jobs.t_stop_time || !jobs.username_1 || !jobs.equipment_no) ? `<button class="sentjob-btn" disabled>ส่งงาน</button>` : `<button class="sentjob-btn" onclick="sentJob(${jobs.job_id}, ${jobs.flight_id})">ส่งงาน</button>`}
          </td>
          <td data-cell="ยกเลิก">
            <button class="caljob-btn" onclick="cancelJob(${jobs.job_id})">ยกเลิก</button>
          </td>
        </tr>`;
}

function maintableList() {
  $.ajax({
    type: "get",
    url: "server/maintable-list.php",
    success: function (data) {
      var response = JSON.parse(data);
      if (response.length === 0) {
        $("#maintable_data").html("<tr><td colspan='14'><h3>กรุณาสร้างงาน</h3></td></tr>");
      } else {
        var rows = response.map(maintable_Row).join("");
        $("#maintable_data").html(rows);
      }
    },
  });
}
// function maintableList() {
//   $.ajax({
//     type: "get",
//     url: "server/maintable-list.php",
//     success: function(data) {
//       var response = JSON.parse(data);
//       var rows = response.map(maintable_Row).join("");
//       $(".loading").hide();
//       $("#maintable_data").html(rows);
//     },
//   });
// }

function addfirst_equipmentType() {
  var newEquipmentType = document.createElement("div");
  newEquipmentType.classList.add("form-equ-group");
  newEquipmentType.innerHTML = `
          <select class="equipment-model" name="equipmentType[]">
            <option value="GPU">GPU</option>
            <option value="ACU">ACU</option>
            <option value="ASU">ASU</option>
            <option value="WSU">WSU</option>
            <option value="TSU">TSU</option>
          </select>
        `;
  document.getElementById("dynamicListbox").appendChild(newEquipmentType);
}

function addEqu() {
  var newEquipmentType = document.createElement("div");
  newEquipmentType.classList.add("form-equ-group");
  newEquipmentType.innerHTML = `
          <select class="equipment-model" name="equipmentType[]">
            <option value="GPU">GPU</option>
            <option value="ACU">ACU</option>
            <option value="ASU">ASU</option>
            <option value="WSU">WSU</option>
            <option value="TSU">TSU</option>
          </select>
          <button type="button" class="btn-delEqu" onclick="removeEquipmentType(this)"> ลบ </button>
        `;
  document.getElementById("dynamicListbox").appendChild(newEquipmentType);
}

function flightSelectList() {
  fetch("server/flightSelect-list.php")
    .then((response) => response.json())
    .then((data) => {
      const flightSelect = document.getElementById("flight-select");
      flightSelect.innerHTML = "";
      data.forEach((flight) => {
        const option = document.createElement("option");
        option.value = flight.flight_no;
        option.value = flight.flight_id;
        option.textContent = flight.flight_no;
        flightSelect.appendChild(option);
      });
    })
    .catch((error) =>
      console.error("Error fetching flight data:", error)
    );
}
window.addEventListener("load", flightSelectList);

function removeEquipmentType(element) {
  element.closest(".form-equ-group").remove();
}

// reset dynamicListbox
function removeequipmentGroups() {
  var equipmentGroups = document.querySelectorAll(".form-equ-group");

  equipmentGroups.forEach(function (group) {
    group.remove();
  });
}

function addFlight() {
  var flight_on = document.getElementById("flight_on").value;
  var register = document.getElementById("register").value;
  var bay = document.getElementById("bay").value;

  if (
    flight_on.trim() === "" ||
    register.trim() === "" ||
    bay.trim() === ""
  ) {
    alert("กรุณากรอกข้อมูลให้ครบทุกช่อง");
    return;
  }

  $.ajax({
    type: "post",
    data: {
      flight_on: flight_on,
      register: register,
      bay: bay,
    },
    url: "server/flight-add.php",
    success: function (data) {
      var response = JSON.parse(data);
      $("#addFlightModal").hide();
      document.getElementById("flight_on").value = "";
      document.getElementById("register").value = "";
      document.getElementById("bay").value = "";
      maintableList();
      flightSelectList();
      // alert(response.message);
    },
  });
}

function addJob() {
  const flight_id = document.getElementById("flight-select").value;
  const equipmentTypes = [];
  const listboxes = document.getElementsByName("equipmentType[]");

  for (let i = 0; i < listboxes.length; i++) {
    equipmentTypes.push(listboxes[i].value);
  }

  if (flight_id === '') {
    alert('กรุณาเลือกเที่ยวบิน');
    return;
  }

  $.ajax({
    type: "post",
    data: {
      flight_id: flight_id,
      equipmentTypes: equipmentTypes,
    },
    url: "server/job-add.php",
    success: function (data) {
      var response = JSON.parse(data);
      $("#addjobModal").hide();
      maintableList();
    },
  });
}

function editFlightData(cell, flight_id, field) {
  var value = cell.textContent.trim();
  $.ajax({
    type: 'POST',
    url: 'server/edit-flight-data.php',
    data: {
      flight_id: flight_id,
      value: value,
      field: field
    },
    success: function (data) {
      var response = JSON.parse(data);
      maintableList();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

function editJobData(cell, job_id, field) {
  var value = cell.textContent.trim();
  $.ajax({
    type: 'POST',
    url: 'server/edit-job-data.php',
    data: {
      job_id: job_id,
      value: value,
      field: field
    },
    success: function (data) {
      var response = JSON.parse(data);
      maintableList();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

function editTime(cell, job_id, field) {
  var value = cell.textContent.trim();
  if (!isValidTimeFormat(value)) {
    alert("รูปแบบเวลาไม่ถูกต้อง");
    maintableList();
    return;
  }

  $.ajax({
    type: 'POST',
    url: 'server/edit-time.php',
    data: {
      job_id: job_id,
      value: value,
      field: field
    },
    success: function (data) {
      var response = JSON.parse(data);
      if (response.status === 'success') {
        maintableList();
      } else {
        console.error(response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

function isValidTimeFormat(value) {
  var regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
  return regex.test(value);
}

function startJob(job_id) {
  var userConfirmation = confirm("กรุณาตรวจสอบ JobID : " + job_id + " คุณต้องการที่จะเริ่มงานหรือไม่?");
  if (userConfirmation) {
    $.ajax({
      type: 'POST',
      data: {
        job_id: job_id,
      },
      url: 'server/start-job.php',
      success: function (data) {
        var response = JSON.parse(data);
        maintableList();
      }
    });
  }
}

function stopJob(job_id) {
  var userConfirmation = confirm("กรุณาตรวจสอบ JobID : " + job_id + " คุณต้องการที่จะหยุดงานหรือไม่?");
  if (userConfirmation) {
    $.ajax({
      type: 'POST',
      data: {
        job_id: job_id,
      },
      url: 'server/stop-job.php',
      success: function (data) {
        var response = JSON.parse(data);
        maintableList();
      }
    });
  }
}

function sentJob(job_id, flight_id) {
  $.ajax({
    type: 'POST',
    url: 'server/sentJob.php',
    data: {
      job_id: job_id,
      flight_id: flight_id,
    },
    success: function (data) {
      var response = JSON.parse(data);
      maintableList();
      flightSelectList();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

function cancelJob(job_id) {
  $.ajax({
    type: 'POST',
    url: 'server/cancelJob.php',
    data: {
      job_id: job_id,
    },
    success: function (data) {
      var response = JSON.parse(data);
      maintableList();
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
}

const showFlightModal = () => {
  document.querySelector(".bg-modal-Flight").style.display = "flex";
};

const hideFlightModal = () => {
  document.querySelector(".bg-modal-Flight").style.display = "none";
  document
    .querySelectorAll('.bg-modal-Flight input[type="text"]')
    .forEach((input) => {
      input.value = "";
    });
};

const closeFlightModal = (event) => {
  if (event.target === document.querySelector(".bg-modal-Flight")) {
    hideFlightModal();
  }
};

const showJobModal = () => {
  document.querySelector(".bg-modal-job").style.display = "flex";
  removeequipmentGroups();
  addfirst_equipmentType();
};

const hideJobModal = () => {
  document.querySelector(".bg-modal-job").style.display = "none";
  document
    .querySelectorAll('.bg-modal-job input[type="text"]')
    .forEach((input) => {
      input.value = "";
    });
};

const closeJobModal = (event) => {
  if (event.target === document.querySelector(".bg-modal-job")) {
    hideJobModal();
  }
};

document
  .getElementById("button-cre-flight")
  .addEventListener("click", showFlightModal);
document
  .getElementById("button-cre-job")
  .addEventListener("click", showJobModal);

document
  .querySelector(".bg-modal-Flight .close")
  .addEventListener("click", hideFlightModal);
document
  .querySelector(".bg-modal-job .close")
  .addEventListener("click", hideJobModal);

document
  .querySelector(".bg-modal-Flight")
  .addEventListener("click", closeFlightModal);
document
  .querySelector(".bg-modal-job")
  .addEventListener("click", closeJobModal);

function convertToUppercase(cell) {
  var value = cell.textContent.trim();
  cell.textContent = value.toUpperCase();
}

function convertToUppercaseTextbox(inputId) {
  var inputElement = document.getElementById(inputId);
  inputElement.value = inputElement.value.toUpperCase();
}

function checkEnterKey(event, cell) {
  if (event.key === 'Enter') {
    cell.blur();
  }
}