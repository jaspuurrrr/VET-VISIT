const calendar = document.querySelector(".calendar"),
  date = document.querySelector(".date"),
  daysContainer = document.querySelector(".days"),
  prev = document.querySelector(".prev"),
  next = document.querySelector(".next"),
  todayBtn = document.querySelector(".today-btn"),
  gotoBtn = document.querySelector(".goto-btn"),
  dateInput = document.querySelector(".date-input"),
  eventDay = document.querySelector(".event-day"),
  eventDate = document.querySelector(".event-date"),
  eventsContainer = document.querySelector(".events"),
  addEventBtn = document.querySelector(".add-event"),
  addEventWrapper = document.querySelector(".add-event-wrapper "),
  addEventCloseBtn = document.querySelector(".close "),
  addEventTitle = document.querySelector(".event-name "),
  addEventFrom = document.querySelector(".event-time-from "),
  addEventTo = document.querySelector(".event-time-to "),
  addEventSubmit = document.querySelector(".add-event-btn ");

const fetchPromises = [];
var logs = [];

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const currentDate = new Date();
const dayOfMonth = currentDate.getDate();

function convertTo24HourFormat(timeString) {
  const match = timeString.match(/^(\d+)(:)?(\d*)?\s*([APMapm]{2})?$/);

  if (!match) {
    // Invalid time format
    return null;
  }

  let hours = parseInt(match[1], 10);
  const minutes = match[3] ? parseInt(match[3], 10) : 0;
  const meridiem = match[4] ? match[4].toUpperCase() : null;

  if (meridiem === 'PM' && hours !== 12) {
    hours += 12;
  } else if (meridiem === 'AM' && hours === 12) {
    hours = 0;
  }

  return hours;
}

//function to add days in days with class day and prev-date next-date on previous month and next month days and active on today
async function initCalendar() {
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  const prevDays = prevLastDay.getDate();
  const lastDate = lastDay.getDate();
  const day = firstDay.getDay();
  const nextDays = 7 - lastDay.getDay() - 1;
  logs.length = 0;



  date.innerHTML = months[month] + " " + year;

  
  let days = "";

  for (let x = day; x > 0; x--) {
    //daysContainer.insertAdjacentHTML('beforeend', `<div class="day prev-date">${prevDays - x + 1}</div>`);
    days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDate; i++) {
    //check if event is present on that day
    let event = false;
    
    if (
      i === new Date().getDate() &&
      year === new Date().getFullYear() &&
      month === new Date().getMonth()
    ) {
      activeDay = i;
      getActiveDay(i);
      updateEvents(i);
      //daysContainer.insertAdjacentHTML('beforeend', `<div class="day today active">${i}</div>`);
      days += `<div class="day today active">${i}</div>`;

    } else if (
      (i < new Date().getDate() &&
      year === new Date().getFullYear() &&
      month === new Date().getMonth()) ||
      (year === new Date().getFullYear() &&
      month < new Date().getMonth()) ||
      (year < new Date().getFullYear())
      ) {
        //daysContainer.insertAdjacentHTML('beforeend', `<div class="day past" >${i}</div>`);
        days += `<div class="day past" >${i}</div>`;
    } else {
      //alert(convertDateFormat(i + " " + months[month] + " " + year))
      const fetchPromise = await fetch('../../php/loaddates.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({date: convertDateFormat(i + " " + months[month] + " " + year)})
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        var adc = 0; //appointment date count
        data.forEach(jsonString => {
          logs.push(jsonString);
          adc++;
        })
        console.log(adc);
        if (adc >= 0 && adc < 16) {
          //daysContainer.insertAdjacentHTML('beforeend', `<div class="day future green">${i}</div>`);
          days += `<div class="day future green">${i}</div>`  
        } else if (adc >= 16 && adc <24){
          //daysContainer.insertAdjacentHTML('beforeend', `<div class="day future yellow">${i}</div>`);
          days += `<div class="day future yellow">${i}</div>`
        } else{
          //daysContainer.insertAdjacentHTML('beforeend', `<div class="day future red">${i}</div>`);
          days += `<div class="day future red">${i}</div>`  
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
      fetchPromises.push(fetchPromise);
    }
  }

  try {
    // Await the completion of all fetch promises
    await Promise.all(fetchPromises);
  
    // Once all fetch promises are resolved, execute this block
    for (let j = 1; j <= nextDays; j++) {
      //daysContainer.insertAdjacentHTML('beforeend', `<div class="day next-date">${j}</div>`);
      days += `<div class="day next-date">${j}</div>`;
    }
    daysContainer.innerHTML = days;
    addListner();
  } catch (error) {
    console.error('Error in fetch promises:', error);
  }
  


  
  //for (let j = 1; j <= nextDays; j++) {
  //  days += `<div class="day next-date">${j}</div>`;
  //}
  //daysContainer.innerHTML = days;
  //addListner();

  //document.querySelector(".today-btn").click();
}

function get12HourTime() {
  
  let hours = currentDate.getHours();
  const ampm = hours >= 12 ? 'PM' : 'AM';

  // Convert hours to 12-hour format
  hours = hours % 12;
  hours = hours ? hours : 12; // 0 should be displayed as 12

  // Construct the 12-hour time string
  const time12Hour = hours + ampm;
  return time12Hour;
}

//alert(get12HourTime());

//function to add month and year on prev and next button
function prevMonth() {
  month--;
  if (month < 0) {
    month = 11;
    year--;
  }
  initCalendar();
}

function nextMonth() {
  month++;
  if (month > 11) {
    month = 0;
    year++;
  }
  initCalendar();
}

prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

initCalendar();


//function to add active on day
function addListner() {
  const days = document.querySelectorAll(".day");
  days.forEach((day) => {
    day.addEventListener("click", (e) => {

      if (day.classList.contains("past") || day.classList.contains("red")){
        return 0;
      }
      getActiveDay(e.target.innerHTML);
      updateEvents(Number(e.target.innerHTML));
      activeDay = Number(e.target.innerHTML);
      //remove active
      days.forEach((day) => {
        day.classList.remove("active");
      });
      //if clicked prev-date or next-date switch to that month
      if (e.target.classList.contains("prev-date")) {
        prevMonth();
        //add active to clicked day afte month is change
        setTimeout(() => {
          //add active where no prev-date or next-date
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("prev-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else if (e.target.classList.contains("next-date")) {
        nextMonth();
        //add active to clicked day afte month is changed
        setTimeout(() => {
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("next-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else {
        e.target.classList.add("active");
      }

      const time_slots = document.querySelectorAll(".time-slot");
      time_slots.forEach(slot => {

        var slotcounter = 0;
        for (let i = 0; i < logs.length; i++) {
          console.log(logs[i]); // Output each element of the array
          var jsonObject = JSON.parse(logs[i]);
          //alert(slot.textContent + jsonObject.time);
          if (slot.textContent == jsonObject.time && (String(month + 1).padStart(2, '0') + "-" + day.textContent + "-" + year) == jsonObject.date){//}.substring(3, 5)){
            slotcounter++;
          }
        }
        if (slotcounter >= 3){
          //alert(slot.textContent + day.textContent)
          slot.classList.add("fullcapacity");
          //slot.disabled = true;
        } else {
          slot.classList.remove("fullcapacity");
          //slot.disabled = true;
        }
        if (day.textContent == dayOfMonth && month == today.getMonth() && year == today.getFullYear()){
          if (parseInt(convertTo24HourFormat(slot.textContent.split(/\s+/)[0])) < parseInt(convertTo24HourFormat(get12HourTime()))){
            slot.classList.add("fullcapacity");
          }
        }
      }
      )


    });

  if (day.classList.contains(".today")){//textContent == dayOfMonth && month == today.getMonth() && year == today.getFullYear()){
          day.click();
        }
  });
  document.querySelector(".today").click();
  //alert("hi");
}

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
  document.querySelector(".today").click();
});



dateInput.addEventListener("input", (e) => {
  dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
  if (dateInput.value.length === 2) {
    dateInput.value += "/";
  }
  if (dateInput.value.length > 7) {
    dateInput.value = dateInput.value.slice(0, 7);
  }
  if (e.inputType === "deleteContentBackward") {
    if (dateInput.value.length === 3) {
      dateInput.value = dateInput.value.slice(0, 2);
    }
  }
});

gotoBtn.addEventListener("click", gotoDate);


function gotoDate() {
  console.log("here");
  const dateArr = dateInput.value.split("/");
  if (dateArr.length === 2) {
    if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
      month = dateArr[0] - 1;
      year = dateArr[1];
      initCalendar();
      return;
    }
  }
  alert("Invalid Date");
}

//function get active day day name and date and update eventday eventdate
function getActiveDay(date) {
  const day = new Date(year, month, date);
  const dayName = day.toString().split(" ")[0];
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = date + " " + months[month] + " " + year;
}

//function update events when a day is active
function updateEvents(date) {
  addTimeSlotListeners();
}

function addTimeSlotListeners() {
  const timeSlots = document.querySelectorAll(".time-slot");
  timeSlots.forEach(slot => {
    slot.addEventListener("click", (date) => {
      if (slot.classList.contains("fullcapacity")){
        return 0;
      }
      const modal = document.getElementById("myModal");
      modal.style.display = "flex";
      modal.style.alignItems = "center";
      modal.style.justifyContent = "center";

      const closeBtn = modal.querySelector(".close");
      closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
      });

      document.getElementById("modal-date").textContent = eventDate.textContent;
      document.getElementById("modal-day").textContent = eventDay.textContent;
      document.getElementById("form-time").textContent = "Time Slot: " + slot.textContent;
    });
  });
}



function convertDateFormat(inputDate) {
    // Define months to map month names to their numerical representation
    const months = {
        January: '01',
        February: '02',
        March: '03',
        April: '04',
        May: '05',
        June: '06',
        July: '07',
        August: '08',
        September: '09',
        October: '10',
        November: '11',
        December: '12'
    };

    // Split the inputDate into day, month, and year
    const [day, monthName, year] = inputDate.split(' ');

    // Get the numerical representation of the month
    const month = months[monthName];

    // Format the date as "mm-dd-yyyy"
    const formattedDate = `${month}-${day}-${year}`;

    return formattedDate;
    }

function convertTime(time) {
  //convert time to 24 hour format
  let timeArr = time.split(":");
  let timeHour = timeArr[0];
  let timeMin = timeArr[1];
  let timeFormat = timeHour >= 12 ? "PM" : "AM";
  timeHour = timeHour % 12 || 12;
  time = timeHour + ":" + timeMin + " " + timeFormat;
  return time;
}

document.addEventListener('DOMContentLoaded', function() {
  // Code to run once the DOM is fully loaded
  //document.querySelector(".today").click();
  //alert("hi");
});