/**
 * Driver Profile – trip data & UI logic
 */

const SUSPEND_REASONS = [
    "Bad weather",
    "Bus breakdown",
    "Road block",
    "Few passengers",
    "Driver emergency",
];

const NOTIFICATION_MINUTES = 15;

const MONTH_NAMES = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December",
];

let selectedMonthIndex = new Date().getMonth();
let selectedYear = new Date().getFullYear();

function createTripStartTime(hoursFromNow, minutes = 0) {
    const d = new Date();
    d.setSeconds(0, 0);
    d.setMinutes(d.getMinutes() + hoursFromNow * 60 + minutes);
    return d;
}

function createTripDate(daysOffset) {
    const d = new Date();
    d.setDate(d.getDate() + daysOffset);
    d.setHours(0, 0, 0, 0);
    return d;
}

function createCalendarDate(year, monthIndex, day) {
    const d = new Date(year, monthIndex, day);
    d.setHours(0, 0, 0, 0);
    return d;
}

function createStartOnDate(date, hours, minutes = 0) {
    const d = new Date(date);
    d.setHours(hours, minutes, 0, 0);
    return d;
}

/** Sample trips – status updated at runtime */
let trips = [
    {
        id: "T-1001",
        number: "TRIP-1001",
        date: createTripDate(0),
        startTime: createTripStartTime(0, 45),
        destination: "Lattakia",
        vehicleId: "BUS-204",
        passengers: 28,
        minPassengers: 15,
        vehicleOk: true,
        status: "upcoming",
        notifiedNear: false,
    },
    {
        id: "T-1002",
        number: "TRIP-1002",
        date: createTripDate(0),
        startTime: createTripStartTime(2, 30),
        destination: "Homs",
        vehicleId: "BUS-118",
        passengers: 8,
        minPassengers: 15,
        vehicleOk: true,
        status: "upcoming",
        notifiedNear: false,
    },
    {
        id: "T-1003",
        number: "TRIP-1003",
        date: createTripDate(0),
        startTime: createTripStartTime(-0.5),
        destination: "Hama",
        vehicleId: "BUS-204",
        passengers: 22,
        minPassengers: 15,
        vehicleOk: true,
        status: "ongoing",
        notifiedNear: true,
    },
    {
        id: "T-1004",
        number: "TRIP-1004",
        date: createTripDate(2),
        startTime: (() => {
            const d = createTripDate(2);
            d.setHours(9, 0, 0, 0);
            return d;
        })(),
        destination: "Damascus",
        vehicleId: "BUS-305",
        passengers: 0,
        minPassengers: 12,
        vehicleOk: false,
        status: "upcoming",
        notifiedNear: false,
    },
    {
        id: "T-1005",
        number: "TRIP-1005",
        date: createTripDate(5),
        startTime: (() => {
            const d = createTripDate(5);
            d.setHours(14, 30, 0, 0);
            return d;
        })(),
        destination: "Raqaa",
        vehicleId: "BUS-118",
        passengers: 18,
        minPassengers: 15,
        vehicleOk: true,
        status: "upcoming",
        notifiedNear: false,
    },
    {
        id: "T-1006",
        number: "TRIP-1006",
        date: createTripDate(12),
        startTime: (() => {
            const d = createTripDate(12);
            d.setHours(7, 15, 0, 0);
            return d;
        })(),
        destination: "Tartous",
        vehicleId: "BUS-204",
        passengers: 30,
        minPassengers: 15,
        vehicleOk: true,
        status: "completed",
        notifiedNear: true,
    },
    {
        id: "T-2001",
        number: "TRIP-2001",
        date: createCalendarDate(2026, 3, 10),
        startTime: createStartOnDate(createCalendarDate(2026, 3, 10), 8, 0),
        destination: "Lattakia",
        vehicleId: "BUS-118",
        passengers: 20,
        minPassengers: 15,
        vehicleOk: true,
        status: "completed",
        notifiedNear: true,
    },
    {
        id: "T-2002",
        number: "TRIP-2002",
        date: createCalendarDate(2026, 3, 22),
        startTime: createStartOnDate(createCalendarDate(2026, 3, 22), 16, 45),
        destination: "Aleppo",
        vehicleId: "BUS-305",
        passengers: 14,
        minPassengers: 15,
        vehicleOk: true,
        status: "completed",
        notifiedNear: true,
    },
    {
        id: "T-2003",
        number: "TRIP-2003",
        date: createCalendarDate(2026, 4, 5),
        startTime: createStartOnDate(createCalendarDate(2026, 4, 5), 11, 30),
        destination: "Damascus",
        vehicleId: "BUS-204",
        passengers: 25,
        minPassengers: 15,
        vehicleOk: true,
        status: "upcoming",
        notifiedNear: false,
    },
];

let suspendTripId = null;
const notifiedTrips = new Set();

function isSameDay(a, b) {
    return (
        a.getFullYear() === b.getFullYear() &&
        a.getMonth() === b.getMonth() &&
        a.getDate() === b.getDate()
    );
}

function isReadyToStart(trip) {
    return trip.passengers >= trip.minPassengers && trip.vehicleOk;
}

function hasTripStarted(trip) {
    return new Date() >= trip.startTime;
}

function formatDate(d) {
    return d.toLocaleDateString(undefined, {
        weekday: "short",
        month: "short",
        day: "numeric",
        year: "numeric",
    });
}

function formatTime(d) {
    return d.toLocaleTimeString(undefined, {
        hour: "2-digit",
        minute: "2-digit",
    });
}

function getCountdown(target) {
    const now = new Date();
    const diff = target - now;

    if (diff <= 0) {
        return { text: "Started", urgent: false, started: true, ms: 0 };
    }

    const h = Math.floor(diff / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const s = Math.floor((diff % 60000) / 1000);
    const urgent = diff < 30 * 60 * 1000;

    const parts = [];
    if (h > 0) parts.push(`${h}h`);
    parts.push(`${String(m).padStart(2, "0")}m`);
    parts.push(`${String(s).padStart(2, "0")}s`);

    return {
        text: parts.join(" "),
        urgent,
        started: false,
        ms: diff,
    };
}

function getDisplayStatus(trip) {
    if (trip.status === "suspended") return "suspended";
    if (trip.status === "completed") return "completed";
    if (trip.status === "ongoing") return "ongoing";
    if (hasTripStarted(trip) && trip.status === "upcoming") return "not-ready";
    return isReadyToStart(trip) ? "ready" : "not-ready";
}

function getStatusLabel(trip) {
    const map = {
        upcoming: "Upcoming",
        ongoing: "Ongoing",
        completed: "Completed",
        suspended: "Suspended",
    };
    return map[trip.status] || trip.status;
}

function getTodayTrips() {
    const today = new Date();
    return trips.filter((t) => isSameDay(t.date, today) && t.status !== "completed");
}

function getWeekTrips() {
    const now = new Date();
    const end = new Date(now);
    end.setDate(end.getDate() + 7);
    return trips.filter((t) => {
        const tripDay = new Date(t.date);
        tripDay.setHours(0, 0, 0, 0);
        const start = new Date(now);
        start.setHours(0, 0, 0, 0);
        return tripDay >= start && tripDay <= end;
    });
}

function parseMonthInput(value) {
    const trimmed = (value || "").trim().toLowerCase();
    if (!trimmed) return null;

    const byName = MONTH_NAMES.findIndex(
        (name) => name.toLowerCase() === trimmed || name.toLowerCase().startsWith(trimmed)
    );
    if (byName >= 0) return byName;

    const asNumber = parseInt(trimmed, 10);
    if (!Number.isNaN(asNumber) && asNumber >= 1 && asNumber <= 12) {
        return asNumber - 1;
    }

    return null;
}

function getTripsForMonth(monthIndex, year) {
    return trips.filter((t) => {
        const d = new Date(t.date);
        return d.getMonth() === monthIndex && d.getFullYear() === year;
    });
}

function showToast(title, body, type = "info") {
    const container = document.getElementById("toastContainer");
    if (!container) return;

    const bg =
        type === "success"
            ? "text-bg-success"
            : type === "warning"
                ? "text-bg-warning"
                : "text-bg-primary";

    const id = `toast-${Date.now()}`;
    const html = `
    <div id="${id}" class="toast ${bg}" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">${title}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body">${body}</div>
    </div>`;
    container.insertAdjacentHTML("beforeend", html);
    const el = document.getElementById(id);
    const toast = new bootstrap.Toast(el, { delay: 5000 });
    toast.show();
    el.addEventListener("hidden.bs.toast", () => el.remove());
}

function checkNearStartNotifications() {
    const now = new Date();
    trips.forEach((trip) => {
        if (trip.status !== "upcoming" || trip.notifiedNear) return;
        const diff = trip.startTime - now;
        const mins = diff / 60000;
        if (mins > 0 && mins <= NOTIFICATION_MINUTES) {
            trip.notifiedNear = true;
            if (!notifiedTrips.has(trip.id)) {
                notifiedTrips.add(trip.id);
                showToast(
                    "Trip starting soon",
                    `${trip.number} to ${trip.destination} starts in ${Math.ceil(mins)} minutes.`,
                    "warning"
                );
            }
        }
    });
}

function buildTripCard(trip, options = {}) {
    const { showActions = false, compact = false } = options;
    const displayStatus = getDisplayStatus(trip);
    const ready = isReadyToStart(trip);
    const countdown = getCountdown(trip.startTime);
    const started = hasTripStarted(trip);
    const cardClass =
        trip.status === "ongoing"
            ? "status-ongoing"
            : `status-${displayStatus}`;

    let actionsHtml = "";
    if (showActions && trip.status !== "completed" && trip.status !== "suspended") {
        const canApology = !started && trip.status === "upcoming";
        const canStart = trip.status === "upcoming" && ready;
        const canComplete = trip.status === "ongoing";
        const canSuspend = started && (trip.status === "upcoming" || trip.status === "ongoing");

        actionsHtml = `<div class="trip-actions">`;
        if (canApology) {
            actionsHtml += `<button type="button" class="btn btn-outline-secondary btn-sm" data-action="apology" data-id="${trip.id}">Apology</button>`;
        }
        if (canStart) {
            actionsHtml += `<button type="button" class="btn btn-baby btn-sm" data-action="start" data-id="${trip.id}">Start Trip</button>`;
        }
        if (canComplete) {
            actionsHtml += `<button type="button" class="btn btn-success btn-sm" data-action="complete" data-id="${trip.id}">Complete Trip</button>`;
        }
        if (canSuspend) {
            actionsHtml += `<button type="button" class="btn btn-outline-danger btn-sm" data-action="suspend" data-id="${trip.id}">Suspend Trip</button>`;
        }
        actionsHtml += `</div>`;
    }

    const readyBadge = ready
        ? '<span class="badge badge-ready">Ready to start</span>'
        : '<span class="badge badge-not-ready">Not ready</span>';

    return `
    <div class="trip-card ${cardClass}" data-trip-id="${trip.id}">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
          <h6 class="mb-1 fw-semibold">${trip.number}</h6>
          <p class="mb-1 small text-muted mb-0">
            <i class="bi bi-calendar3 me-1"></i>${formatDate(trip.date)} &middot; ${formatTime(trip.startTime)}
          </p>
          <p class="mb-1"><i class="bi bi-geo-alt me-1"></i>${trip.destination}</p>
          <p class="mb-0 small">
            Vehicle:
            <a href="vehicle.html?id=${encodeURIComponent(trip.vehicleId)}" class="vehicle-link">${trip.vehicleId}</a>
            &middot; Passengers: ${trip.passengers}/${trip.minPassengers}
          </p>
        </div>
        <div class="text-end">
          <span class="badge badge-status bg-secondary mb-2">${getStatusLabel(trip)}</span>
          <div><span class="countdown ${countdown.urgent ? "urgent" : ""} ${countdown.started ? "started" : ""}" data-countdown="${trip.id}">${countdown.text}</span></div>
          ${!compact ? `<div class="mt-2">${readyBadge}</div>` : ""}
        </div>
      </div>
      ${actionsHtml}
    </div>`;
}

function renderSidebar() {
    const el = document.getElementById("sidebarTrips");
    if (!el) return;

    const todayTrips = getTodayTrips().sort((a, b) => a.startTime - b.startTime);

    if (todayTrips.length === 0) {
        el.innerHTML = '<p class="text-muted small mb-0">No trips scheduled for today.</p>';
        return;
    }

    el.innerHTML = todayTrips
        .map((trip) => {
            const countdown = getCountdown(trip.startTime);
            const ongoing = trip.status === "ongoing";
            return `
        <div class="sidebar-trip ${ongoing ? "ongoing" : ""}" data-sidebar-trip="${trip.id}">
          <strong class="d-block">${trip.number}</strong>
          <small>${trip.destination}</small>
          <div class="mt-1">
            <span class="countdown ${countdown.urgent ? "urgent" : ""} ${countdown.started ? "started" : ""}" data-countdown="${trip.id}">${countdown.text}</span>
          </div>
        </div>`;
        })
        .join("");
}

function renderTasksRecord() {
    const el = document.getElementById("tasksRecord");
    if (!el) return;

    const pending = trips
        .filter((t) => t.status === "upcoming" || t.status === "ongoing")
        .sort((a, b) => a.startTime - b.startTime);

    el.innerHTML =
        pending.length === 0
            ? '<p class="text-muted">No pending trips.</p>'
            : pending.map((t) => buildTripCard(t, { showActions: false })).join("");
}

function getDailyTrips() {
    const today = new Date();
    return trips
        .filter((t) => isSameDay(t.date, today))
        .sort((a, b) => a.startTime - b.startTime);
}

function renderDailyTrips() {
    const el = document.getElementById("dailyTrips");
    if (!el) return;

    const daily = getDailyTrips();

    el.innerHTML =
        daily.length === 0
            ? '<p class="text-muted">No trips scheduled for today.</p>'
            : daily.map((t) => buildTripCard(t, { showActions: false })).join("");
}

function updateMonthSearchLabel() {
    const label = document.getElementById("monthSearchLabel");
    if (!label) return;
    const monthName = MONTH_NAMES[selectedMonthIndex];
    const count = getTripsForMonth(selectedMonthIndex, selectedYear).length;
    label.textContent = `Showing ${count} trip${count === 1 ? "" : "s"} for ${monthName} ${selectedYear}`;
}

function renderMonthTrips() {
    const el = document.getElementById("monthTrips");
    if (!el) return;

    const monthTrips = getTripsForMonth(selectedMonthIndex, selectedYear);
    updateMonthSearchLabel();
    renderTabTrips("monthTrips", monthTrips);
}

function initMonthSearch() {
    const datalist = document.getElementById("monthOptions");
    const input = document.getElementById("monthSearch");
    if (!datalist || !input) return;

    datalist.innerHTML = MONTH_NAMES.map((m) => `<option value="${m}">`).join("");

    const now = new Date();
    input.value = MONTH_NAMES[now.getMonth()];
    selectedMonthIndex = now.getMonth();
    selectedYear = now.getFullYear();

    const applyMonth = () => {
        const parsed = parseMonthInput(input.value);
        if (parsed === null) {
            showToast("Invalid month", "Please enter a full month name (e.g. April).", "warning");
            input.value = MONTH_NAMES[selectedMonthIndex];
            return;
        }
        selectedMonthIndex = parsed;
        selectedYear = now.getFullYear();
        input.value = MONTH_NAMES[parsed];
        renderMonthTrips();
    };

    input.addEventListener("change", applyMonth);
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            applyMonth();
        }
    });
}

function renderTabTrips(containerId, tripList) {
    const el = document.getElementById(containerId);
    if (!el) return;

    const sorted = [...tripList].sort((a, b) => a.startTime - b.startTime);
    el.innerHTML =
        sorted.length === 0
            ? '<p class="text-muted">No trips in this period.</p>'
            : sorted.map((t) => buildTripCard(t, { showActions: true })).join("");
}

function renderAll() {
    renderSidebar();
    // renderTasksRecord();
    renderDailyTrips();
    renderTabTrips("weekTrips", getWeekTrips());
    renderMonthTrips();
}

function updateCountdowns() {
    document.querySelectorAll("[data-countdown]").forEach((el) => {
        const id = el.getAttribute("data-countdown");
        const trip = trips.find((t) => t.id === id);
        if (!trip) return;
        const cd = getCountdown(trip.startTime);
        el.textContent = cd.text;
        el.classList.toggle("urgent", cd.urgent);
        el.classList.toggle("started", cd.started);
    });
}

function handleTripAction(action, tripId) {
    const trip = trips.find((t) => t.id === tripId);
    if (!trip) return;

    switch (action) {
        case "start":
            if (!isReadyToStart(trip)) {
                showToast("Cannot start", "Passengers or vehicle condition not met.", "warning");
                return;
            }
            trip.status = "ongoing";
            showToast("Trip started", `${trip.number} is now ongoing. Passengers notified.`, "success");
            break;
        case "complete":
            trip.status = "completed";
            showToast("Trip completed", `${trip.number} marked as completed.`, "success");
            break;
        case "apology":
            if (hasTripStarted(trip)) {
                showToast("Not available", "Apology is only available before the trip starts.", "warning");
                return;
            }
            showToast("Apology sent", `Apology notification sent for ${trip.number}.`, "info");
            break;
        case "suspend":
            if (!hasTripStarted(trip)) {
                showToast("Not available", "Suspend is only available after the trip start time.", "warning");
                return;
            }
            suspendTripId = tripId;
            document.getElementById("suspendTripLabel").textContent = trip.number;
            const modal = new bootstrap.Modal(document.getElementById("suspendModal"));
            modal.show();
            return;
        default:
            return;
    }
    renderAll();
}

function confirmSuspend(reason) {
    const trip = trips.find((t) => t.id === suspendTripId);
    if (!trip) return;
    trip.status = "suspended";
    trip.suspendReason = reason;
    bootstrap.Modal.getInstance(document.getElementById("suspendModal"))?.hide();
    showToast("Trip suspended", `${trip.number}: ${reason}`, "warning");
    suspendTripId = null;
    renderAll();
}

function bindEvents() {
    document.body.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-action]");
        if (!btn) return;
        const action = btn.getAttribute("data-action");
        const id = btn.getAttribute("data-id");
        if (action && id) handleTripAction(action, id);
    });

    document.getElementById("confirmSuspend")?.addEventListener("click", () => {
        const selected = document.querySelector('input[name="suspendReason"]:checked');
        if (!selected) {
            showToast("Select a reason", "Please choose a suspension reason.", "warning");
            return;
        }
        confirmSuspend(selected.value);
    });
}

function initDriverProfile() {
    bindEvents();
    initMonthSearch();
    renderAll();
    checkNearStartNotifications();
    setInterval(() => {
        updateCountdowns();
        checkNearStartNotifications();
    }, 1000);
}

/** Vehicle page */
function initVehiclePage() {
    const params = new URLSearchParams(window.location.search);
    const vehicleId = params.get("id") || "BUS-204";

    const vehicles = {
        "BUS-204": {
            model: "Mercedes Tourismo",
            year: 2022,
            capacity: 45,
            plate: "ABC-2040",
            condition: "Good",
            conditionOk: true,
            lastService: "2026-05-15",
            mileage: "124,500 km",
        },
        "BUS-118": {
            model: "Volvo 9700",
            year: 2021,
            capacity: 50,
            plate: "XYZ-1188",
            condition: "Good",
            conditionOk: true,
            lastService: "2026-04-28",
            mileage: "98,200 km",
        },
        "BUS-305": {
            model: "Scania Touring",
            year: 2019,
            capacity: 48,
            plate: "DEF-3055",
            condition: "Maintenance required",
            conditionOk: false,
            lastService: "2026-02-10",
            mileage: "210,800 km",
        },
    };

    const v = vehicles[vehicleId] || vehicles["BUS-204"];
    document.getElementById("vehicleTitle").textContent = vehicleId;
    document.getElementById("vehicleId").textContent = vehicleId;
    document.getElementById("vehicleModel").textContent = v.model;
    document.getElementById("vehicleYear").textContent = v.year;
    document.getElementById("vehicleCapacity").textContent = v.capacity;
    document.getElementById("vehiclePlate").textContent = v.plate;
    document.getElementById("vehicleCondition").textContent = v.condition;
    document.getElementById("vehicleCondition").className = v.conditionOk
        ? "condition-ok fw-semibold"
        : "condition-warn fw-semibold";
    document.getElementById("vehicleService").textContent = v.lastService;
    document.getElementById("vehicleMileage").textContent = v.mileage;
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.body.dataset.page === "vehicle") {
        initVehiclePage();
    } else {
        initDriverProfile();
    }
});
