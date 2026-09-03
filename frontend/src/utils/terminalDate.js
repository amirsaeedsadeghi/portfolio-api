const WEEKDAYS = [
    "Sun",
    "Mon",
    "Tue",
    "Wed",
    "Thu",
    "Fri",
    "Sat",
];

const MONTHS = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

function pad(value) {
    return String(value).padStart(2, "0");
}

export function formatTerminalDate(date = new Date()) {
    const weekday = WEEKDAYS[date.getDay()];
    const month = MONTHS[date.getMonth()];
    const day = date.getDate();

    const hours = pad(date.getHours());
    const minutes = pad(date.getMinutes());
    const seconds = pad(date.getSeconds());

    return `${weekday} ${month} ${day} ${hours}:${minutes}:${seconds}`;
}