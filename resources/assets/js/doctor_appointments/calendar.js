document.addEventListener('turbo:load', loadDoctorAppointmentCalendar)

let popover
let popoverState = false
let appointmentStatusId = null
let doctorAppointmentCalendar
let data = {
    id: '',
    uId: '',
    eventName: '',
    eventDescription: '',
    eventStatus: '',
    startDate: '',
    endDate: '',
    amount: 0,
    service: '',
    patientName: '',
}

// View event variables
let viewEventName, viewEventDescription, viewEventStatus, viewStartDate,
    viewEndDate,
    viewModal,
    viewService,
    viewUId,
    viewAmount

function loadDoctorAppointmentCalendar () {
    initCalendarApp()
    init()
}

const initCalendarApp = function () {
    if (usersRole != 'doctor') {
        return;
    }
    let calendarEl = document.getElementById('doctorAppointmentCalendar')

    if (!$(calendarEl).length) {
        return
    }
    let lang = $('.currentLanguage').val()
    doctorAppointmentCalendar = new FullCalendar.Calendar(calendarEl, {
        locale: lang,
        themeSystem: 'bootstrap5',
        height: 750,
        buttonText: {
            today: Lang.get('messages.datepicker.today'),
            day: Lang.get('messages.admin_dashboard.day'),
            month: Lang.get('messages.admin_dashboard.month'),
        },
        headerToolbar: {
            left: 'title',
            center: 'prev,next today',
            right: 'dayGridDay,dayGridMonth',
        },
        initialDate: new Date(),
        timeZone: 'UTC',
        dayMaxEvents: true,
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url: route('doctors.appointments.calendar'),
                type: 'GET',
                data: info,
                success: function (result) {
                    if (result.success) {
                        successCallback(result.data)
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message)
                    failureCallback()
                },
            })
        },
        // MouseEnter event --- more info: https://fullcalendar.io/docs/eventMouseEnter
        eventMouseEnter: function (arg) {
            formatArgs({
                id: arg.event.id,
                title: arg.event.title,
                startStr: arg.event.startStr,
                endStr: arg.event.endStr,
                description: arg.event.extendedProps.description,
                status: arg.event.extendedProps.status,
                amount: arg.event.extendedProps.amount,
                uId: arg.event.extendedProps.uId,
                service: arg.event.extendedProps.service,
                patientName: arg.event.extendedProps.patientName,
            })

            // Show popover preview
            initPopovers(arg.el)
        },
        eventMouseLeave: function () {
            hidePopovers()
        },
        // Click event --- more info: https://fullcalendar.io/docs/eventClick
        eventClick: function (arg) {
            hidePopovers()
            appointmentStatusId = arg.event.id
            formatArgs({
                id: arg.event.id,
                title: arg.event.title,
                startStr: arg.event.startStr,
                endStr: arg.event.endStr,
                description: arg.event.extendedProps.description,
                status: arg.event.extendedProps.status,
                amount: arg.event.extendedProps.amount,
                uId: arg.event.extendedProps.uId,
                service: arg.event.extendedProps.service,
                patientName: arg.event.extendedProps.patientName,
            })
            handleViewEvent()
        },
    })
    doctorAppointmentCalendar.render()
}

const init = () => {
    if (!$('#doctorAppointmentCalendarModal').length) {
        return
    }
    const viewElement = document.getElementById(
        'doctorAppointmentCalendarModal')
    viewModal = new bootstrap.Modal(viewElement)
    viewEventName = viewElement.querySelector(
        '[data-calendar="event_name"]')
    viewEventDescription = viewElement.querySelector(
        '[data-calendar="event_description"]')
    viewEventStatus = viewElement.querySelector(
        '[data-calendar="event_status"]')
    viewAmount = viewElement.querySelector('[data-calendar="event_amount"]')
    viewUId = viewElement.querySelector('[data-calendar="event_uId"]')
    viewService = viewElement.querySelector(
        '[data-calendar="event_service"]')
    viewStartDate = viewElement.querySelector(
        '[data-calendar="event_start_date"]')
    viewEndDate = viewElement.querySelector(
        '[data-calendar="event_end_date"]')
}

// Format FullCalendar responses
const formatArgs = (res) => {
    data.id = res.id
    data.eventName = res.title
    data.eventStatus = res.status
    data.startDate = res.startStr
    data.endDate = res.endStr
    data.amount = res.amount
    data.uId = res.uId
    data.service = res.service
    data.patientName = res.patientName
}

// Initialize popovers --- more info: https://getbootstrap.com/docs/4.0/components/popovers/
const initPopovers = (element) => {
    hidePopovers()

    // Generate popover content
    const startDate = data.allDay ? moment(data.startDate).
        format('Do MMM, YYYY') : moment(data.startDate).
        format('Do MMM, YYYY - h:mm a')
    const endDate = data.allDay
        ? moment(data.endDate).format('Do MMM, YYYY')
        : moment(data.endDate).format('Do MMM, YYYY - h:mm a')
    const popoverHtml = '<div class="fw-bolder mb-2"><b>Patient:</b> ' +
        data.patientName +
        '</div><div class="fs-7"><span class="fw-bold">Start:</span> ' +
        startDate +
        '</div><div class="fs-7 mb-4"><span class="fw-bold">End:</span> ' +
        endDate + '</div>'

    // Popover options
    let options = {
        container: 'body',
        trigger: 'manual',
        boundary: 'window',
        placement: 'auto',
        dismiss: true,
        html: true,
        title: 'Appointment Details',
        content: popoverHtml,
    }
}

// Hide active popovers
const hidePopovers = () => {
    if (popoverState) {
        popover.dispose()
        popoverState = false
    }
}

// Handle view event
const handleViewEvent = () => {
    $('.fc-popover').addClass('hide')
    viewModal.show()

    // Detect all day event
    let eventNameMod
    let startDateMod
    let endDateMod
    let book = $('#bookCalenderConst').val();
    let checkIn = $('#checkInCalenderConst').val();
    let checkOut = $('#checkOutCalenderConst').val();
    let cancel = $('#cancelCalenderConst').val();

    eventNameMod = ''
    startDateMod = moment(data.startDate).utc().format('Do MMM, YYYY - h:mm A')
    endDateMod = moment(data.endDate).utc().format('Do MMM, YYYY - h:mm A')
    viewEndDate.innerText = ': ' + endDateMod
    viewStartDate.innerText = ': ' + startDateMod

    // Populate view data
    viewEventName.innerText = 'Patient: ' + data.patientName
    $(viewEventStatus).empty()
    $(viewEventStatus).append(`
<option class="booked" disabled value="${book}" ${data.eventStatus == book
        ? 'selected'
        : ''}>${Lang.get('messages.appointment.booked')}</option>
<option value="${checkIn}" ${data.eventStatus == checkIn
        ? 'selected'
        : ''} ${data.eventStatus == checkIn ? 'selected' : ''}
    ${(data.eventStatus == cancel || data.eventStatus == checkOut)
        ? 'disabled'
        : ''}>${Lang.get('messages.appointment.check_in')}</option>
<option value="${checkOut}" ${data.eventStatus == checkOut ? 'selected' : ''}
    ${(data.eventStatus == cancel || data.eventStatus == book)
        ? 'disabled'
        : ''}>${Lang.get('messages.appointment.check_out')}</option>
<option value="${cancel}" ${data.eventStatus == cancel
        ? 'selected'
        : ''} ${data.eventStatus == checkIn ? 'disabled' : ''}
   ${data.eventStatus == checkOut ? 'disabled' : ''}>${Lang.get('messages.appointment.cancelled')}</option>
`)
    $(viewEventStatus).val(data.eventStatus).trigger('change')
    viewAmount.innerText = addCommas(data.amount)
    viewUId.innerText = data.uId
    viewService.innerText = data.service
}

listenChange('.doctor-apptnt-calendar-status-change', function () {
    if (!$(this).val()) {
        return false
    }

    let appointmentStatus = $(this).val()
    let appointmentId = appointmentStatusId
    if (parseInt(appointmentStatus) === data.eventStatus) {
        return false
    }
    $.ajax({
        url: route('doctors.change-status', appointmentId),
        type: 'POST',
        data: {
            appointmentId: appointmentId,
            appointmentStatus: appointmentStatus,
        },
        success: function (result) {
            displaySuccessMessage(result.message)
            $('#doctorAppointmentCalendarModal').modal('hide')
            doctorAppointmentCalendar.refetchEvents()
        },
    })
})
