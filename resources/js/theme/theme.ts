import { createTheme } from '@mui/material/styles';

export const theme = createTheme({
    palette: {
        mode: 'dark',
        primary: {
            main: '#6366f1',
            light: '#818cf8',
            dark: '#4f46e5',
            contrastText: '#ffffff',
        },
        secondary: {
            main: '#a855f7',
            light: '#c084fc',
            dark: '#7e22ce',
        },
        background: {
            default: '#0f172a',
            paper: '#1e293b',
        },
        text: {
            primary: '#f8fafc',
            secondary: '#94a3b8',
        },
    },
    typography: {
        fontFamily: "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
        h4: {
            fontWeight: 700,
            letterSpacing: '-0.5px',
        },
        h6: {
            fontWeight: 600,
        },
        button: {
            textTransform: 'none',
            fontWeight: 600,
            borderRadius: '10px',
        },
    },
    shape: {
        borderRadius: 12,
    },
    components: {
        MuiCssBaseline: {
            styleOverrides: {
                '*:focus, *:focus-visible, input:focus, textarea:focus, select:focus': {
                    outline: 'none !important',
                    boxShadow: 'none !important',
                },
            },
        },
        MuiButton: {
            styleOverrides: {
                root: {
                    padding: '10px 20px',
                    fontSize: '0.95rem',
                },
                contained: {
                    background: 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)',
                    boxShadow: '0 8px 16px rgba(79, 70, 229, 0.3)',
                    '&:hover': {
                        background: 'linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)',
                        boxShadow: '0 10px 20px rgba(79, 70, 229, 0.4)',
                    },
                },
            },
        },
        MuiPaper: {
            styleOverrides: {
                root: {
                    backgroundImage: 'none',
                },
            },
        },
        MuiOutlinedInput: {
            styleOverrides: {
                root: {
                    borderRadius: '10px',
                    backgroundColor: 'rgba(15, 23, 42, 0.6)',
                    '& .MuiOutlinedInput-notchedOutline': {
                        borderColor: 'rgba(255, 255, 255, 0.15)',
                    },
                    '&:hover .MuiOutlinedInput-notchedOutline': {
                        borderColor: '#6366f1',
                    },
                    '&.Mui-focused .MuiOutlinedInput-notchedOutline': {
                        borderColor: '#6366f1',
                        borderWidth: '1.5px',
                    },
                },
                input: {
                    outline: 'none !important',
                    boxShadow: 'none !important',
                    '&:focus, &:focus-visible': {
                        outline: 'none !important',
                        boxShadow: 'none !important',
                    },
                },
            },
        },
    },
});

export default theme;
