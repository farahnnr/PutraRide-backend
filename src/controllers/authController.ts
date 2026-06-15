import { Request, Response } from 'express';
import db from '../config/db';
import { RowDataPacket, ResultSetHeader } from 'mysql2';

// ==========================================
// 1. FUNGSI PENDAFTARAN (REGISTER)
// ==========================================
export const registerUser = async (req: Request, res: Response): Promise<void> => {
  try {
    const { name, email, username, password, userType, vehicleType, licensePlate } = req.body;

    const [existingUsers] = await db.query<RowDataPacket[]>(
      'SELECT id FROM users WHERE email = ?',
      [email]
    );

    if (existingUsers.length > 0) {
      res.status(400).json({ message: 'Email sudah pun berdaftar!' });
      return;
    }

    let finalVehicleType = null;
    let finalLicensePlate = null;

    if (userType === 'driver') {
      if (!vehicleType) {
        res.status(400).json({ message: 'Driver wajib memilih jenis kenderaan!' });
        return;
      }
      finalVehicleType = vehicleType;
      finalLicensePlate = licensePlate || '';
    }

    const [result] = await db.query<ResultSetHeader>(
      'INSERT INTO users (name, email, username, password, userType, vehicleType, licensePlate) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [name, email, username, password, userType, finalVehicleType, finalLicensePlate]
    );

    res.status(201).json({
      message: 'Pendaftaran berjaya disimpan ke MySQL!',
      user: { id: result.insertId, name, email, userType }
    });

  } catch (error) {
    res.status(500).json({ message: 'Ralat pada server', error });
  }
};

// ==========================================
// 2. FUNGSI LOG MASUK (LOGIN) - KOD ANDA DI SINI
// ==========================================
export const loginUser = async (req: Request, res: Response): Promise<void> => {
  try {
    const { email, password } = req.body;

    // 1. Cari pengguna berdasarkan email di dalam database MySQL
    const [users] = await db.query<RowDataPacket[]>(
      'SELECT id, name, email, password, userType, vehicleType FROM users WHERE email = ?',
      [email]
    );

    if (users.length === 0) {
      res.status(401).json({ message: 'Email atau password salah!' });
      return;
    }

    const user = users[0];

    // 2. Padankan kata laluan
    if (user.password !== password) {
      res.status(401).json({ message: 'Email atau password salah!' });
      return;
    }

    // 3. Jika berjaya, hantar semula maklumat pengguna ke frontend
    res.status(200).json({
      message: 'Log masuk berjaya!',
      user: {
        id: user.id,
        name: user.name,
        email: user.email,
        userType: user.userType,
        vehicleType: user.vehicleType
      }
    });

  } catch (error) {
    res.status(500).json({ message: 'Ralat pada server semasa log masuk', error });
  }
};