import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Box, CircularProgress, Container, Typography } from '@mui/material';
import { Storefront as AppLogoIcon } from '@mui/icons-material';
import useAuth from '../hooks/useAuth';

export const SplashScreen: React.FC = () => {
    const { isAuthenticated } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        const timer = setTimeout(() => {
            if (isAuthenticated) {
                navigate('/dashboard', { replace: true });
            } else {
                navigate('/login', { replace: true });
            }
        }, 2000);

        return () => clearTimeout(timer);
    }, [isAuthenticated, navigate]);

    return (
        <Box
            sx={{
                minHeight: '100vh',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                background: 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%)',
                color: '#ffffff',
                textAlign: 'center',
                padding: 3,
            }}
        >
            <Container maxWidth="xs">
                <Box
                    sx={{
                        display: 'flex',
                        flexDirection: 'column',
                        alignItems: 'center',
                        justifyContent: 'center',
                    }}
                >
                    <Box
                        sx={{
                            width: 88,
                            height: 88,
                            borderRadius: 5,
                            background: 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            marginBottom: 3,
                            boxShadow: '0 12px 28px rgba(79, 70, 229, 0.4)',
                        }}
                    >
                        <AppLogoIcon sx={{ fontSize: 48, color: '#ffffff' }} />
                    </Box>

                    <Typography variant="h4" sx={{ fontWeight: 800, mb: 1, letterSpacing: '-0.5px' }}>
                        MultiVendor
                    </Typography>

                    <Typography variant="body1" color="text.secondary" sx={{ mb: 4, opacity: 0.85 }}>
                        Mobile & Web Platform
                    </Typography>

                    <CircularProgress size={36} color="primary" sx={{ mb: 3 }} />

                    <Typography variant="caption" color="text.secondary" sx={{ opacity: 0.6 }}>
                        Loading application resources...
                    </Typography>
                </Box>
            </Container>
        </Box>
    );
};

export default SplashScreen;
