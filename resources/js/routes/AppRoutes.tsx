import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';
import EditProfile from '../pages/EditProfile';
import Login from '../pages/Login';
import Register from '../pages/Register';
import SplashScreen from '../pages/SplashScreen';
import ProtectedRoute from './ProtectedRoute';

export const AppRoutes: React.FC = () => {
    return (
        <Routes>
            {/* Startup Splash Screen */}
            <Route path="/" element={<SplashScreen />} />
            <Route path="/splash" element={<SplashScreen />} />

            {/* Public Auth Routes */}
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />

            {/* Protected Application Routes */}
            <Route element={<ProtectedRoute />}>
                <Route path="/dashboard" element={<Dashboard />} />
                <Route path="/profile/edit" element={<EditProfile />} />
            </Route>

            {/* Fallback Navigation */}
            <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
    );
};

export default AppRoutes;
