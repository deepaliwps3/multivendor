import React, { useEffect, useState } from "react";
import { Link as RouterLink, useNavigate } from "react-router-dom";
import {
    Alert,
    Autocomplete,
    Box,
    Button,
    Chip,
    CircularProgress,
    Container,
    IconButton,
    InputAdornment,
    Link,
    Paper,
    Step,
    StepLabel,
    Stepper,
    TextField,
    Typography,
} from "@mui/material";
import {
    ArrowBack as ArrowBackIcon,
    ArrowForward as ArrowForwardIcon,
    Email as EmailIcon,
    LocationOn as LocationIcon,
    Lock as LockIcon,
    Person as PersonIcon,
    Phone as PhoneIcon,
    ReceiptLong as GstIcon,
    Storefront as BusinessIcon,
    Visibility,
    VisibilityOff,
} from "@mui/icons-material";
import useAuth from "../hooks/useAuth";
import authApi, { Industry, Service } from "../api/authApi";

export const Register: React.FC = () => {
    const [activeStep, setActiveStep] = useState(0);

    // Step 0 — Account fields
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [phone, setPhone] = useState("");
    const [password, setPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);

    // Step 1 — Business fields
    const [businessName, setBusinessName] = useState("");
    const [address, setAddress] = useState("");
    const [gst_number, setGstNumber] = useState("");

    // Step 2 — Industry & Service selection
    const [industries, setIndustries] = useState<Industry[]>([]);
    const [services, setServices] = useState<Service[]>([]);
    const [selectedIndustries, setSelectedIndustries] = useState<Industry[]>(
        [],
    );
    const [selectedServices, setSelectedServices] = useState<Service[]>([]);
    const [loadingIndustries, setLoadingIndustries] = useState(false);
    const [loadingServices, setLoadingServices] = useState(false);

    const [stepError, setStepError] = useState<string | null>(null);

    const { register, isAuthenticated, loading, error, resetError } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (isAuthenticated) {
            navigate("/dashboard", { replace: true });
        }
    }, [isAuthenticated, navigate]);

    // Fetch industries once, on mount
    useEffect(() => {
        const fetchIndustries = async () => {
            setLoadingIndustries(true);
            try {
                const data = await authApi.getIndustries();
                setIndustries(data);
            } catch (err) {
                console.error("Failed to load industries", err);
            } finally {
                setLoadingIndustries(false);
            }
        };
        fetchIndustries();
    }, []);

    // Fetch services whenever selected industries change
    useEffect(() => {
        if (selectedIndustries.length === 0) {
            setServices([]);
            setSelectedServices([]);
            return;
        }

        const fetchServices = async () => {
            setLoadingServices(true);
            try {
                const industryIds = selectedIndustries.map((i) => i.id);
                const data = await authApi.getServices(industryIds);
                setServices(data);
                // Drop any previously selected services that no longer belong
                // to the currently selected industries
                setSelectedServices((prev) =>
                    prev.filter((s) => data.some((d) => d.id === s.id)),
                );
            } catch (err) {
                console.error("Failed to load services", err);
            } finally {
                setLoadingServices(false);
            }
        };
        fetchServices();
    }, [selectedIndustries]);

    const handleNext = () => {
        setStepError(null);

        if (activeStep === 0) {
            if (!name || !email || !password) {
                setStepError(
                    "Please complete all required account fields (Name, Email, Password).",
                );
                return;
            }
            if (password.length < 8) {
                setStepError("Password must be at least 8 characters long.");
                return;
            }
        }

        setActiveStep((prev) => prev + 1);
    };

    const handleBack = () => {
        setStepError(null);
        setActiveStep((prev) => prev - 1);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        resetError();
        setStepError(null);

        if (selectedIndustries.length === 0) {
            setStepError("Please select at least one industry.");
            return;
        }
        if (selectedServices.length === 0) {
            setStepError("Please select at least one service.");
            return;
        }

        await register({
            name,
            email,
            phone,
            password,
            gst_number,
            address,
            business_name: businessName,
            industry_ids: selectedIndustries.map((i) => i.id),
            service_ids: selectedServices.map((s) => s.id),
        });
    };

    const steps = ["Account Info", "Business Profile", "Industry & Services"];

    const stepDescriptions = [
        "Step 1: Your Account Information",
        "Step 2: Business Profile Details",
        "Step 3: Select Your Industry & Services",
    ];

    return (
        <Box
            sx={{
                minHeight: "100vh",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                background:
                    "linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%)",
                padding: { xs: 2, sm: 3 },
            }}
        >
            <Container maxWidth="xs" disableGutters sx={{ px: 1 }}>
                <Paper
                    elevation={16}
                    sx={{
                        padding: { xs: 3, sm: 4 },
                        display: "flex",
                        flexDirection: "column",
                        borderRadius: 4,
                        backdropFilter: "blur(16px)",
                        background: "rgba(30, 41, 59, 0.85)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                    }}
                >
                    {/* Header */}
                    <Box sx={{ textAlign: "center", mb: 3 }}>
                        <Typography
                            component="h1"
                            variant="h5"
                            sx={{
                                fontWeight: 800,
                                mb: 0.5,
                                letterSpacing: "-0.5px",
                            }}
                        >
                            Register Account
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                            {stepDescriptions[activeStep]}
                        </Typography>
                    </Box>

                    {/* Stepper Progress */}
                    <Stepper
                        activeStep={activeStep}
                        alternativeLabel
                        sx={{ mb: 3 }}
                    >
                        {steps.map((label) => (
                            <Step key={label}>
                                <StepLabel
                                    slotProps={{
                                        label: {
                                            style: {
                                                fontSize: "0.8rem",
                                                color: "#94a3b8",
                                            },
                                        },
                                    }}
                                >
                                    {label}
                                </StepLabel>
                            </Step>
                        ))}
                    </Stepper>

                    {/* Error Alerts */}
                    {(stepError || error) && (
                        <Alert
                            severity="error"
                            sx={{
                                width: "100%",
                                mb: 2.5,
                                borderRadius: 2,
                                fontSize: "0.875rem",
                            }}
                        >
                            {stepError || error}
                        </Alert>
                    )}

                    <Box
                        component="form"
                        onSubmit={handleSubmit}
                        sx={{ width: "100%" }}
                    >
                        {/* STEP 0: Account Details */}
                        {activeStep === 0 && (
                            <Box
                                sx={{
                                    display: "flex",
                                    flexDirection: "column",
                                    gap: 1.5,
                                }}
                            >
                                <TextField
                                    margin="dense"
                                    required
                                    fullWidth
                                    id="name"
                                    label="Full Name *"
                                    name="name"
                                    autoComplete="name"
                                    value={name}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setName(e.target.value)}
                                    placeholder="Enter full name"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment position="start">
                                                    <PersonIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <TextField
                                    margin="dense"
                                    required
                                    fullWidth
                                    id="email"
                                    label="Email Address *"
                                    name="email"
                                    type="email"
                                    autoComplete="email"
                                    value={email}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setEmail(e.target.value)}
                                    placeholder="user@example.com"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment position="start">
                                                    <EmailIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <TextField
                                    margin="dense"
                                    fullWidth
                                    id="phone"
                                    label="Phone Number (Optional)"
                                    name="phone"
                                    autoComplete="tel"
                                    value={phone}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setPhone(e.target.value)}
                                    placeholder="+91 98765 43210"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment position="start">
                                                    <PhoneIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <TextField
                                    margin="dense"
                                    required
                                    fullWidth
                                    name="password"
                                    label="Password *"
                                    type={showPassword ? "text" : "password"}
                                    id="password"
                                    autoComplete="new-password"
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
                                />

                                <Button
                                    fullWidth
                                    variant="contained"
                                    size="large"
                                    onClick={handleNext}
                                    endIcon={<ArrowForwardIcon />}
                                    sx={{
                                        mt: 2,
                                        py: 1.5,
                                        fontSize: "0.95rem",
                                        fontWeight: 600,
                                    }}
                                >
                                    Continue to Business Info
                                </Button>
                            </Box>
                        )}

                        {/* STEP 1: Business Details */}
                        {activeStep === 1 && (
                            <Box
                                sx={{
                                    display: "flex",
                                    flexDirection: "column",
                                    gap: 1.5,
                                }}
                            >
                                <TextField
                                    margin="dense"
                                    fullWidth
                                    id="business_name"
                                    label="Business / Store Name"
                                    name="business_name"
                                    value={businessName}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setBusinessName(e.target.value)}
                                    placeholder="e.g. Acme Enterprise"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment position="start">
                                                    <BusinessIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <TextField
                                    margin="dense"
                                    fullWidth
                                    id="address"
                                    label="Business Address"
                                    name="address"
                                    multiline
                                    rows={2}
                                    value={address}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setAddress(e.target.value)}
                                    placeholder="Building, Street, City"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment
                                                    position="start"
                                                    sx={{
                                                        alignSelf: "flex-start",
                                                        mt: 1,
                                                    }}
                                                >
                                                    <LocationIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <TextField
                                    margin="dense"
                                    fullWidth
                                    id="gst_number"
                                    label="GST Number (Optional)"
                                    name="gst_number"
                                    value={gst_number}
                                    onChange={(
                                        e: React.ChangeEvent<HTMLInputElement>,
                                    ) => setGstNumber(e.target.value)}
                                    placeholder="27AAAAA0000A1Z5"
                                    slotProps={{
                                        input: {
                                            startAdornment: (
                                                <InputAdornment position="start">
                                                    <GstIcon color="action" />
                                                </InputAdornment>
                                            ),
                                        },
                                    }}
                                />

                                <Box sx={{ display: "flex", gap: 1.5, mt: 2 }}>
                                    <Button
                                        variant="outlined"
                                        size="large"
                                        onClick={handleBack}
                                        startIcon={<ArrowBackIcon />}
                                        sx={{ flex: 1, py: 1.5 }}
                                    >
                                        Back
                                    </Button>

                                    <Button
                                        fullWidth
                                        variant="contained"
                                        size="large"
                                        onClick={handleNext}
                                        endIcon={<ArrowForwardIcon />}
                                        sx={{
                                            flex: 2,
                                            py: 1.5,
                                            fontSize: "0.95rem",
                                            fontWeight: 600,
                                        }}
                                    >
                                        Continue to Industry & Services
                                    </Button>
                                </Box>
                            </Box>
                        )}

                        {/* STEP 2: Industry & Service Selection */}
                        {activeStep === 2 && (
                            <Box
                                sx={{
                                    display: "flex",
                                    flexDirection: "column",
                                    gap: 1.5,
                                }}
                            >
                                <Autocomplete<Industry, true, false, false>
                                    multiple
                                    options={industries}
                                    getOptionLabel={(option) => option.name}
                                    isOptionEqualToValue={(option, value) =>
                                        option.id === value.id
                                    }
                                    loading={loadingIndustries}
                                    value={selectedIndustries}
                                    onChange={(_, newValue) =>
                                        setSelectedIndustries(newValue)
                                    }
                                    renderInput={(params) => (
                                        <TextField
                                            {...params}
                                            label="Select Industries *"
                                            placeholder="Jewellery, Textile..."
                                            margin="dense"
                                        />
                                    )}
                                />

                                <Autocomplete<Service, true, false, false>
                                    multiple
                                    options={services}
                                    getOptionLabel={(option) => option.name}
                                    isOptionEqualToValue={(option, value) =>
                                        option.id === value.id
                                    }
                                    loading={loadingServices}
                                    value={selectedServices}
                                    onChange={(_, newValue) =>
                                        setSelectedServices(newValue)
                                    }
                                    disabled={selectedIndustries.length === 0}
                                    renderInput={(params) => (
                                        <TextField
                                            {...params}
                                            label="Select Services *"
                                            placeholder={
                                                selectedIndustries.length
                                                    ? "Making, Polishing..."
                                                    : "Select an industry first"
                                            }
                                            margin="dense"
                                        />
                                    )}
                                />

                                <Box sx={{ display: "flex", gap: 1.5, mt: 2 }}>
                                    <Button
                                        variant="outlined"
                                        size="large"
                                        onClick={handleBack}
                                        startIcon={<ArrowBackIcon />}
                                        sx={{ flex: 1, py: 1.5 }}
                                    >
                                        Back
                                    </Button>

                                    <Button
                                        type="submit"
                                        variant="contained"
                                        size="large"
                                        disabled={loading}
                                        sx={{
                                            flex: 2,
                                            py: 1.5,
                                            fontSize: "0.95rem",
                                            fontWeight: 600,
                                        }}
                                    >
                                        {loading ? (
                                            <CircularProgress
                                                size={26}
                                                color="inherit"
                                            />
                                        ) : (
                                            "Complete Registration"
                                        )}
                                    </Button>
                                </Box>
                            </Box>
                        )}

                        {/* Sign In Link Footer */}
                        <Box sx={{ mt: 3, textAlign: "center" }}>
                            <Typography variant="body2" color="text.secondary">
                                Already have an account?{" "}
                                <Link
                                    component={RouterLink}
                                    to="/login"
                                    color="primary"
                                    sx={{
                                        fontWeight: 600,
                                        textDecoration: "none",
                                        "&:hover": {
                                            textDecoration: "underline",
                                        },
                                    }}
                                >
                                    Sign In
                                </Link>
                            </Typography>
                        </Box>
                    </Box>
                </Paper>
            </Container>
        </Box>
    );
};

export default Register;
