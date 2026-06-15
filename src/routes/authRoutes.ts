import { Router } from 'express';
import { registerUser, loginUser } from '../controllers/authController'; // Pastikan loginUser di-import

const router = Router();

router.post('/register', registerUser);
router.post('/login', loginUser); // ⚡ Tambah baris laluan ini

export default router;