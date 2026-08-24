import { createAsyncThunk, createSlice, PayloadAction } from '@reduxjs/toolkit';
import authApi, { AuthResponse, LoginCredentials, RegisterCredentials, User } from '../../api/authApi';

export interface AuthState {
    user: User | null;
    token: string | null;
    isAuthenticated: boolean;
    loading: boolean;
    error: string | null;
}

const savedToken = localStorage.getItem('auth_token');
const savedUser = localStorage.getItem('auth_user');

const initialState: AuthState = {
    user: savedUser ? JSON.parse(savedUser) : null,
    token: savedToken || null,
    isAuthenticated: !!savedToken,
    loading: false,
    error: null,
};

// Async Thunks
export const loginUser = createAsyncThunk<AuthResponse, LoginCredentials, { rejectValue: string }>(
    'auth/loginUser',
    async (credentials, { rejectWithValue }) => {
        try {
            const data = await authApi.login(credentials);
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('auth_user', JSON.stringify(data.user));
            return data;
        } catch (err: any) {
            const message = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || err.message || 'Login failed';
            return rejectWithValue(message);
        }
    }
);

export const registerUser = createAsyncThunk<AuthResponse, RegisterCredentials, { rejectValue: string }>(
    'auth/registerUser',
    async (credentials, { rejectWithValue }) => {
        try {
            const data = await authApi.register(credentials);
            localStorage.setItem('auth_token', data.token);
            localStorage.setItem('auth_user', JSON.stringify(data.user));
            return data;
        } catch (err: any) {
            const message = err.response?.data?.message || err.response?.data?.errors?.email?.[0] || err.message || 'Registration failed';
            return rejectWithValue(message);
        }
    }
);

export const logoutUser = createAsyncThunk<void, void>(
    'auth/logoutUser',
    async () => {
        try {
            await authApi.logout();
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
        }
    }
);

export const fetchCurrentUser = createAsyncThunk<User, void, { rejectValue: string }>(
    'auth/fetchCurrentUser',
    async (_, { rejectWithValue }) => {
        try {
            const user = await authApi.getMe();
            localStorage.setItem('auth_user', JSON.stringify(user));
            return user;
        } catch (err: any) {
            return rejectWithValue('Failed to fetch user');
        }
    }
);

const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        setCredentials: (state, action: PayloadAction<{ user: User; token: string }>) => {
            state.user = action.payload.user;
            state.token = action.payload.token;
            state.isAuthenticated = true;
            state.error = null;
        },
        logout: (state) => {
            state.user = null;
            state.token = null;
            state.isAuthenticated = false;
            state.error = null;
            localStorage.removeItem('auth_token');
            localStorage.removeItem('auth_user');
        },
        clearError: (state) => {
            state.error = null;
        },
    },
    extraReducers: (builder) => {
        builder
            // Login
            .addCase(loginUser.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(loginUser.fulfilled, (state, action) => {
                state.loading = false;
                state.user = action.payload.user;
                state.token = action.payload.token;
                state.isAuthenticated = true;
                state.error = null;
            })
            .addCase(loginUser.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload || 'Login failed';
            })
            // Register
            .addCase(registerUser.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(registerUser.fulfilled, (state, action) => {
                state.loading = false;
                state.user = action.payload.user;
                state.token = action.payload.token;
                state.isAuthenticated = true;
                state.error = null;
            })
            .addCase(registerUser.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload || 'Registration failed';
            })
            // Logout
            .addCase(logoutUser.fulfilled, (state) => {
                state.user = null;
                state.token = null;
                state.isAuthenticated = false;
                state.loading = false;
                state.error = null;
            })
            // Fetch Current User
            .addCase(fetchCurrentUser.fulfilled, (state, action) => {
                state.user = action.payload;
            });
    },
});

export const { setCredentials, logout, clearError } = authSlice.actions;
export default authSlice.reducer;
