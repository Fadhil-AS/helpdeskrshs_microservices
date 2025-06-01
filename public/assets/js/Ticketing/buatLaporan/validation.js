function validateFileGlobal(file) { // Ubah nama agar tidak konflik jika ada fungsi validateFile lain
    const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    const maxSize = 5 * 1024 * 1024; // 5MB
    if (!allowedTypes.includes(file.type)) {
        return {
            valid: false,
            message: `Tipe file tidak diizinkan: ${file.name}. Harap unggah JPG, PNG, atau PDF.`
        };
    }
    if (file.size > maxSize) {
        return {
            valid: false,
            message: `Ukuran file ${file.name} terlalu besar. Maksimal 5MB.`
        };
    }
    return {
        valid: true,
        message: ''
    };
}
