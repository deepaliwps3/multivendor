import { LoginCredentials, RegisterCredentials } from '../api/authApi';
import { clearError, loginUser, logoutUser, registerUser } from '../store/slices/authSlice';
import { useAppDispatch, useAppSelector } from './redux';

export const useAuth = () => {
    const dispatch = useAppDispatch();
    const { user, token, isAuthenticated, loading, error } = useAppSelector((state) => state.auth);

    const login = (credentials: LoginCredentials) => dispatch(loginUser(credentials));
    const register = (credentials: RegisterCredentials) => dispatch(registerUser(credentials));
    const logout = () => dispatch(logoutUser());
    const resetError = () => dispatch(clearError());

    return {
        user,
        token,
        isAuthenticated,
        loading,
        error,
        login,
        register,
        logout,
        resetError,
    };
};

export default useAuth;
