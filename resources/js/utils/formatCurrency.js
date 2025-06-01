export default function formatCurrency(amount) {
    if (amount === null || amount === undefined) return '0';
    const formattedAmount = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);

    return formattedAmount.slice(0, -3);
}
