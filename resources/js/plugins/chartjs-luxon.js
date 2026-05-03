import * as luxon from 'luxon';

if (typeof window !== 'undefined' && typeof window.luxon === 'undefined') {
    window.luxon = luxon;
}
