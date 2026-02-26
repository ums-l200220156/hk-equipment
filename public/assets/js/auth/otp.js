// Auto focus ke kolom berikutnya saat mengetik
function moveFocus(current, nextId) {
    if (current.value.length >= 1) {
        document.getElementById(nextId).focus();
    }
}