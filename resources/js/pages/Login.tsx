import React, { useEffect, useState } from "react";
import { Link as RouterLink, useNavigate } from "react-router-dom";
import {
    Alert,
    Box,
    Button,
    CircularProgress,
    Container,
    IconButton,
    InputAdornment,
    Link,
    Paper,
    TextField,
    Typography,
} from "@mui/material";
import {
    Email as EmailIcon,
    Lock as LockIcon,
    Smartphone as MobileIcon,
    Visibility,
    VisibilityOff,
} from "@mui/icons-material";
import useAuth from "../hooks/useAuth";

export const Login: React.FC = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);

    const { login, isAuthenticated, loading, error, resetError } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (isAuthenticated) {
            navigate("/dashboard", { replace: true });
        }
    }, [isAuthenticated, navigate]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        resetError();
        await login({ email, password });
    };

    return (
        <Box
            sx={{
                minHeight: "100vh",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                background:
                    "linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%)",
                padding: 3,
            }}
        >
            <Container maxWidth="xs">
                <Paper
                    elevation={12}
                    sx={{
                        padding: 4,
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "center",
                        borderRadius: 4,
                        backdropFilter: "blur(16px)",
                        background: "rgba(30, 41, 59, 0.85)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                    }}
                >
                    <Box
                        sx={{
                            width: 56,
                            height: 56,
                            borderRadius: 3,
                            background:
                                "linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)",
                            display: "flex",
                            alignItems: "center",
                            justifyContent: "center",
                            marginBottom: 2,
                            boxShadow: "0 8px 16px rgba(79, 70, 229, 0.3)",
                        }}
                    >
                        <MobileIcon sx={{ fontSize: 32, color: "#ffffff" }} />
                    </Box>

                    <Typography
                        component="h1"
                        variant="h5"
                        sx={{ fontWeight: 700, mb: 0.5, textAlign: "center" }}
                    >
                        Login
                    </Typography>
                    {/* <Typography variant="body2" color="text.secondary" sx={{ mb: 3, textAlign: 'center' }}>
                        Sign in to access your Mobile & Web application
                    </Typography> */}

                    {error && (
                        <Alert
                            severity="error"
                            sx={{ width: "100%", mb: 3, borderRadius: 2 }}
                        >
                            {error}
                        </Alert>
                    )}

                    <Box
                        component="form"
                        onSubmit={handleSubmit}
                        sx={{ width: "100%" }}
                    >
                        <TextField
                            margin="normal"
                            required
                            fullWidth
                            id="email"
                            label="Email Address"
                            name="email"
                            autoComplete="email"
                            autoFocus
                            value={email}
                            onChange={(
                                e: React.ChangeEvent<HTMLInputElement>,
                            ) => setEmail(e.target.value)}
                            placeholder="vendor@example.com"
                            slotProps={{
                                input: {
                                    startAdornment: (
                                        <InputAdornment position="start">
                                            <EmailIcon color="action" />
                                        </InputAdornment>
                                    ),
                                },
                            }}
                            sx={{ mb: 2 }}
                        />

                        <TextField
                            margin="normal"
                            required
                            fullWidth
                            name="password"
                            label="Password"
                            type={showPassword ? "text" : "password"}
                            id="password"
                            autoComplete="current-password"
                            value={password}
                            onChange={(
                                e: React.ChangeEvent<HTMLInputElement>,
                            ) => setPassword(e.target.value)}
                            placeholder="••••••••"
                            slotProps={{
                                input: {
                                    startAdornment: (
                                        <InputAdornment position="start">
                                            <LockIcon color="action" />
                                        </InputAdornment>
                                    ),
                                    endAdornment: (
                                        <InputAdornment position="end">
                                            <IconButton
                                                aria-label="toggle password visibility"
                                                onClick={() =>
                                                    setShowPassword(
                                                        (prev) => !prev,
                                                    )
                                                }
                                                edge="end"
                                            >
                                                {showPassword ? (
                                                    <VisibilityOff />
                                                ) : (
                                                    <Visibility />
                                                )}
                                            </IconButton>
                                        </InputAdornment>
                                    ),
                                },
                            }}
                            sx={{ mb: 3 }}
                        />

                        <Button
                            type="submit"
                            fullWidth
                            variant="contained"
                            disabled={loading}
                            size="large"
                            sx={{ py: 1.5, fontSize: "1rem", fontWeight: 600 }}
                        >
                            {loading ? (
                                <CircularProgress size={26} color="inherit" />
                            ) : (
                                "Sign In"
                            )}
                        </Button>

                        <Box sx={{ mt: 2.5, textAlign: "center" }}>
                            <Typography variant="body2" color="text.secondary">
                                Don't have an account?{" "}
                                <Link
                                    component={RouterLink}
                                    to="/register"
                                    color="primary"
                                    sx={{
                                        fontWeight: 600,
                                        textDecoration: "none",
                                        "&:hover": {
                                            textDecoration: "underline",
                                        },
                                    }}
                                >
                                    Create Account
                                </Link>
                            </Typography>
                        </Box>
                    </Box>
                </Paper>
            </Container>
        </Box>
    );
};

export default Login;
