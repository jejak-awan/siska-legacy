import { useForm, useField } from 'vee-validate'
import { yup } from '../plugins/validation.js'
import { useToast } from '../plugins/toast.js'

/**
 * Composable for form validation using VeeValidate + Yup
 * @param {Object} schema - Yup validation schema
 * @param {Object} initialValues - Initial form values
 * @param {Object} options - Additional options
 */
export function useValidation(schema, initialValues = {}, options = {}) {
  const toast = useToast()
  
  // Setup form with VeeValidate
  const form = useForm({
    validationSchema: schema,
    initialValues,
    ...options
  })

  // Handle form submission
  const handleSubmit = async (onSubmit, onError = null) => {
    try {
      const { valid } = await form.validate()
      
      if (valid) {
        await onSubmit(form.values)
        return { success: true, data: form.values }
      } else {
        toast.error('Mohon perbaiki error pada form')
        return { success: false, errors: form.errors.value }
      }
    } catch (error) {
      console.error('Form submission error:', error)
      
      if (onError) {
        onError(error)
      } else {
        toast.error('Terjadi kesalahan saat mengirim form')
      }
      
      return { success: false, error }
    }
  }

  // Reset form
  const resetForm = () => {
    form.resetForm()
  }

  // Set form values
  const setValues = (newValues) => {
    form.setValues(newValues)
  }

  // Set field error
  const setFieldError = (field, message) => {
    form.setFieldError(field, message)
  }

  // Clear field error
  const clearFieldError = (field) => {
    form.clearErrors(field)
  }

  return {
    // Form state
    values: form.values,
    errors: form.errors,
    meta: form.meta,
    isValid: form.meta.value.valid,
    isSubmitting: form.meta.value.isSubmitting,
    
    // Form methods
    handleSubmit,
    resetForm,
    setValues,
    setFieldError,
    clearFieldError,
    validate: form.validate,
    validateField: form.validateField,
  }
}

/**
 * Composable for individual field validation
 * @param {string} name - Field name
 * @param {Object} rules - Validation rules
 * @param {*} initialValue - Initial field value
 */
export function useFieldValidation(name, rules, initialValue = '') {
  const field = useField(name, rules, {
    initialValue,
    validateOnValueUpdate: true,
  })

  return {
    value: field.value,
    errorMessage: field.errorMessage,
    meta: field.meta,
    handleChange: field.handleChange,
    handleBlur: field.handleBlur,
    validate: field.validate,
  }
}

/**
 * Common validation schemas
 */
export const validationSchemas = {
  // Siswa form schema
  siswa: yup.object({
    // Account info
    username: yup.string()
      .required('Username wajib diisi')
      .username('Username tidak valid')
      .min(3, 'Username minimal 3 karakter')
      .max(20, 'Username maksimal 20 karakter'),
    
    email: yup.string()
      .required('Email wajib diisi')
      .email('Format email tidak valid'),
    
    password: yup.string()
      .when('$isEdit', {
        is: false,
        then: (schema) => schema.required('Password wajib diisi'),
        otherwise: (schema) => schema.notRequired()
      })
      .min(6, 'Password minimal 6 karakter'),
    
    // Student identity
    nisn: yup.string()
      .required('NISN wajib diisi')
      .nisn('NISN harus 10 digit'),
    
    nis: yup.string()
      .required('NIS wajib diisi')
      .min(1, 'NIS tidak boleh kosong'),
    
    nik: yup.string()
      .required('NIK wajib diisi')
      .nik('NIK harus 16 digit'),
    
    // Personal info
    nama_lengkap: yup.string()
      .required('Nama lengkap wajib diisi')
      .min(2, 'Nama minimal 2 karakter')
      .max(100, 'Nama maksimal 100 karakter'),
    
    nama_panggilan: yup.string()
      .max(50, 'Nama panggilan maksimal 50 karakter'),
    
    jenis_kelamin: yup.string()
      .required('Jenis kelamin wajib dipilih')
      .oneOf(['L', 'P'], 'Jenis kelamin tidak valid'),
    
    tempat_lahir: yup.string()
      .required('Tempat lahir wajib diisi')
      .min(2, 'Tempat lahir minimal 2 karakter'),
    
    tanggal_lahir: yup.date()
      .required('Tanggal lahir wajib diisi')
      .max(new Date(), 'Tanggal lahir tidak boleh di masa depan'),
    
    agama: yup.string()
      .required('Agama wajib dipilih')
      .oneOf(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'], 'Agama tidak valid'),
    
    kewarganegaraan: yup.string()
      .required('Kewarganegaraan wajib dipilih')
      .oneOf(['WNI', 'WNA'], 'Kewarganegaraan tidak valid'),
    
    // Academic info
    kelas_id: yup.number()
      .nullable()
      .transform((value, originalValue) => {
        return originalValue === '' ? null : value
      }),
    
    angkatan: yup.string()
      .required('Angkatan wajib diisi')
      .matches(/^\d{4}$/, 'Angkatan harus 4 digit tahun'),
    
    status_siswa: yup.string()
      .required('Status siswa wajib dipilih')
      .oneOf(['Aktif', 'Lulus', 'Pindah', 'Keluar', 'Mengundurkan Diri'], 'Status siswa tidak valid'),
    
    // Contact info
    nomor_hp: yup.string()
      .phone('Nomor HP tidak valid'),
    
    email_pribadi: yup.string()
      .email('Format email pribadi tidak valid'),
    
    alamat_lengkap: yup.string()
      .required('Alamat lengkap wajib diisi')
      .min(10, 'Alamat minimal 10 karakter'),
    
    kelurahan: yup.string()
      .required('Kelurahan wajib diisi'),
    
    kecamatan: yup.string()
      .required('Kecamatan wajib diisi'),
    
    kabupaten_kota: yup.string()
      .required('Kabupaten/Kota wajib diisi'),
    
    provinsi: yup.string()
      .required('Provinsi wajib diisi'),
  }),

  // Reference data schema
  referenceCategory: yup.object({
    name: yup.string()
      .required('Nama kategori wajib diisi')
      .min(2, 'Nama kategori minimal 2 karakter')
      .max(100, 'Nama kategori maksimal 100 karakter'),
    
    description: yup.string()
      .max(500, 'Deskripsi maksimal 500 karakter'),
    
    fields_config: yup.array()
      .of(yup.object({
        name: yup.string().required('Nama field wajib diisi'),
        label: yup.string().required('Label field wajib diisi'),
        type: yup.string().required('Tipe field wajib dipilih'),
        required: yup.boolean(),
      }))
      .min(1, 'Minimal 1 field harus dikonfigurasi'),
  }),

  referenceData: yup.object({
    category_id: yup.number()
      .required('Kategori wajib dipilih')
      .positive('Kategori tidak valid'),
    
    code: yup.string()
      .max(50, 'Kode maksimal 50 karakter'),
    
    name: yup.string()
      .required('Nama data wajib diisi')
      .min(2, 'Nama data minimal 2 karakter')
      .max(100, 'Nama data maksimal 100 karakter'),
    
    description: yup.string()
      .max(500, 'Deskripsi maksimal 500 karakter'),
  }),

  // Login schema
  login: yup.object({
    username: yup.string()
      .required('Username atau email wajib diisi'),
    
    password: yup.string()
      .required('Password wajib diisi')
      .min(1, 'Password tidak boleh kosong'),
    
    remember: yup.boolean(),
  }),
}

export default {
  useValidation,
  useFieldValidation,
  validationSchemas,
}
