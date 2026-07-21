const numberFormatter = (num) => {
    if (typeof num == 'undefined') {
        return 0;
    }
    const thousandSeparator = '.';
  
    const splitted = num.toString().split('.');
    const decimal = splitted[1] ? splitted[1] : '';
    const integer = splitted[0];
  
    let formattedNumber = String(integer).replace(/\B(?=(\d{3})+(?!\d))/g, `${thousandSeparator}`);
  
    return formattedNumber + (decimal ? `,${decimal}` : '');
};

const alphaNumeric = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z']
const monthName = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
const monthNameShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

const indonesianDate = (date) => {
    try {
        
        const d = new Date(date)
        const month = d.getMonth()
        const day = d.getDate()
        const year = d.getFullYear()
        return `${day} ${monthName[month]} ${year}`
    } catch (error) {
        return 'n/a'
    }
}

export {
    numberFormatter, alphaNumeric, monthName, monthNameShort, indonesianDate
}