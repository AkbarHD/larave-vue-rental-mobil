export const formatDate = (dateString) => {
    if (!dateString) return '-';
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};
export default formatDate;
