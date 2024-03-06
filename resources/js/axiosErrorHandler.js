import Swal from 'sweetalert2';

export function axiosErrorHandler(error) {
    if (error.response && error.response.status === 422) {
        const errorData = error.response.data;
        if (errorData.errors) {
            const errorMessages = Object.values(errorData.errors).flat().map(errorMessage => errorMessage.replace(/\.$/, "")).join(", ");
            Swal.fire({ title: 'Failed', text: errorMessages, icon: 'warning' });
        } else if (errorData.message) {
            Swal.fire({ title: 'Failed', text: errorData.message, icon: 'warning' });
        } else {
            Swal.fire({ title: 'Failed', text: 'An unknown error occurred.', icon: 'warning' });
        }
    } else {
        Swal.fire({ title: 'Failed', text: error.message, icon: 'warning' });
    }
}
