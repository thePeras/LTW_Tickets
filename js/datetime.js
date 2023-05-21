
/**
 * 
 * @param {number} date1 in unixepoch format in seconds
 * @param {number} date2 in unixepoch format in seconds
 * @returns {string} formatted string
 */
function formatTimeDifference(date1, date2){
    const diff = Math.abs(date1 - date2); //in seconds
    const inMinutes = Math.floor(diff / 60);
    const inHours = Math.floor(inMinutes / 60);
    const inDays = Math.floor(inHours / 24);
    const inMonths = Math.floor(inDays / 30);
    const inYears = Math.floor(inMonths / 12);
    if (inYears > 0) {
        return `${inYears} year(s) ago`;
    } else if (inMonths > 0) {
        return `${inMonths} month(s) ago`;
    } else if (inDays > 0) {
        return `${inDays} day(s) ago`;
    } else if (inHours > 0) {
        return `${inHours} hour(s) ago`;
    } else if (inMinutes > 0) {
        return `${inMinutes} minute(s) ago`;
    } 
    return '%s second(s) ago';


}
