import { configure } from 'vee-validate'
import { localize, setLocale } from '@vee-validate/i18n'
import * as yup from 'yup'

// Configure VeeValidate
configure({
  // Use Yup as the validation engine
  validateOnInput: true,
  validateOnChange: true,
  validateOnBlur: true,
  validateOnModelUpdate: true,
})

// Set default locale to Indonesian (simplified)
setLocale('id')

// Extend Yup with custom validation methods
yup.addMethod(yup.string, 'nisn', function (message = 'NISN harus 10 digit') {
  return this.test('nisn', message, function (value) {
    if (!value) return true // Allow empty if not required
    return /^\d{10}$/.test(value)
  })
})

yup.addMethod(yup.string, 'nik', function (message = 'NIK harus 16 digit') {
  return this.test('nik', message, function (value) {
    if (!value) return true // Allow empty if not required
    return /^\d{16}$/.test(value)
  })
})

yup.addMethod(yup.string, 'phone', function (message = 'Nomor HP tidak valid') {
  return this.test('phone', message, function (value) {
    if (!value) return true // Allow empty if not required
    return /^(\+62|62|0)[0-9]{9,13}$/.test(value)
  })
})

yup.addMethod(yup.string, 'username', function (message = 'Username hanya boleh huruf, angka, dan underscore') {
  return this.test('username', message, function (value) {
    if (!value) return true // Allow empty if not required
    return /^[a-zA-Z0-9_]+$/.test(value)
  })
})

// Export configured yup
export { yup }
export default yup
