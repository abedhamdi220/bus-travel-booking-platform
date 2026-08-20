var today = new Date().toISOString().split("T")[0];
// document.getElementById("date").setAttribute("min", today);
const dateInput = document.getElementById("date");
if (dateInput) {
  dateInput.setAttribute("min", today);
}
function goToCompanyPage() {
  const from = document.getElementById("fromSelect").value;
  const to = document.getElementById("toSelect").value;
  const date = document.getElementById("date").value;
  const passengers = document.querySelector("input[name='passengers']").value;
  const company = document.getElementById("companySelect").value;

  // تحقق إنو كل الحقول معبّية
  if (!from || !to || !date || !passengers || !company) {
    alert("Please fill all fields before searching!");
    return false; // يمنع الانتقال إذا في نقص
  }

  // إذا كل شي معبّي → يفتح صفحة الشركة
  // window.location.href = company + ".html";
  window.location.href = company + ".html?from=" + from + "&to=" + to + "&time=" + date + "&passengers=" + passengers;
  return false; // يمنع الفورم من الإرسال الافتراضي
}

//------------------------search page------------------------------------
const params = new URLSearchParams(window.location.search);
const fromCity = params.get("from");
const toCity = params.get("to");
const time = params.get("time");
const passengers = params.get("passengers");

// تعبئة الحقول
if (fromCity) document.getElementById("fromCity").value = fromCity;
if (toCity) document.getElementById("toCity").value = toCity;
if (time) document.getElementById("date").value = time;
if (passengers) document.getElementById("passengers").value = passengers;

const card = document.querySelectorAll(".fromto");
card.forEach(card => {
  card.innerHTML = ` <div class="fromto col-lg-4  d-flex justify-content-between align-items-center">
                        <h4 class=" fromcity fw-bold">${fromCity}</h4>
                        <h4> <i class="fa-solid fa-arrow-right fw-bold"></i></h4>
                        <h4 class=" tocity fw-bold">${toCity}</h4>
                    </div>

                </div>
`;
})








function updateSearch(e) {
  e.preventDefault(); // يمنع إعادة تحميل الصفحة

  var fromCity = document.getElementById("fromCity").value;
  var toCity = document.getElementById("toCity").value;
  var date = document.getElementById("date").value;
  var passengers = document.getElementById("passengers").value;



  document.getElementById("results").innerHTML = `
    <div class="alert alert-info mt-5 ">
      <p>From: ${fromCity}</p>
      <p>To: ${toCity}</p>
      <p>Date: ${date}</p>
      <p>Passengers: ${passengers}</p>
    </div>
  `;
  document.getElementById("tripText").innerHTML =
    `Showing trips from <span class='fw-bold' style="color: green;">${fromCity}</span>
     to <span class='fw-bold' style="color: green;">${toCity}</span>
     on ${date}`;


  const card = document.querySelectorAll(".fromto");
  card.forEach(card => {
    card.innerHTML = ` <div class="fromto col-lg-4  d-flex justify-content-between align-items-center">
                        <h4 class=" fromcity fw-bold">${fromCity}</h4>
                        <h4> <i class="fa-solid fa-arrow-right fw-bold"></i></h4>
                        <h4 class=" tocity fw-bold">${toCity}</h4>
                    </div>

                </div>
`;
  })



}
//---------------signout-------------------------------




function validateForm1() {

  var password = document.getElementById("newPassword").value;
  var confirmPassword = document.getElementById("confirm-Password").value;




  // تحقق من تطابق كلمة المرور
  if (password !== confirmPassword) {
    alert("Passwords don't match!");
    return false;
  }

  return true; // إذا كل شيء صحيح، نرسل البيانات للسيرفر
}





function validateForm() {

  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirmPassword").value;




  // تحقق من تطابق كلمة المرور
  if (password !== confirmPassword) {
    alert("Passwords don't match!");
    return false;
  }

  return true; // إذا كل شيء صحيح، نرسل البيانات للسيرفر
}

// ----------------------booking------------------------------------------------------



function applyOfferType() {
  let offer = document.getElementById("offerSelect").value;
  let discount = 0;

  if (offer === "vip") {
    discount = totalPrice * 0.20;
  }

  if (offer === "weekend") {
    discount = totalPrice * 0.10;
  }

  if (offer === "ramadan") {
    discount = totalPrice * 0.15;
  }

  if (offer === "family" && selectedSeats.length >= 3) {
    discount = selectedSeats.length * 5;
  }

  if (offer === "group" && selectedSeats.length >= 5) {
    discount = totalPrice * 0.20;
  }

  totalPrice = originalPrice - discount;
  updateSummary();
}
//------------------payment---------------------------
