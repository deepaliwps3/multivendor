import React, { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";
import {
    ArrowBack as ArrowBackIcon,
    Save as SaveIcon,
} from "@mui/icons-material";
import {
    Alert,
    Autocomplete,
    Box,
    Button,
    Checkbox,
    CircularProgress,
    Container,
    Divider,
    IconButton,
    Paper,
    Snackbar,
    Stack,
    TextField,
    Typography,
} from "@mui/material";
import { authApi, Industry, Service } from "../api/authApi";
import { vendorApi } from "../api/vendorApi";

export const EditProfile: React.FC = () => {
    const navigate = useNavigate();

    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [successMsg, setSuccessMsg] = useState<string | null>(null);

    // Lookups
    const [allIndustries, setAllIndustries] = useState<Industry[]>([]);
    const [allServices, setAllServices] = useState<Service[]>([]);

    // Form fields
    const [businessName, setBusinessName] = useState("");
    const [contactPerson, setContactPerson] = useState("");
    const [address, setAddress] = useState("");
    const [gstNumber, setGstNumber] = useState("");
    const [selectedIndustry, setSelectedIndustry] = useState<Industry | null>(
        null,
    );
    const [selectedServices, setSelectedServices] = useState<Service[]>([]);

    useEffect(() => {
        const loadProfileAndLookups = async () => {
            setLoading(true);
            try {
                const [profile, indList, srvList] = await Promise.all([
                    vendorApi.getMe(),
                    authApi.getIndustries(),
                    authApi.getServices(),
                ]);

                setAllIndustries(indList);
                setAllServices(srvList);

                // Populate pre-filled form values
                setBusinessName(profile.business_name || "");
                setContactPerson(profile.contact_person || "");
                setAddress(profile.address || "");
                setGstNumber(profile.gst_number || "");

                // Map single selected industry & services
                const profileIndId = profile.industries?.[0]?.id;
                const currentInd =
                    indList.find((i) => i.id === profileIndId) || null;
                setSelectedIndustry(currentInd);

                const profileSrvIds = profile.services.map((s) => s.id);
                setSelectedServices(
                    srvList.filter((s) => profileSrvIds.includes(s.id)),
                );
            } catch (err) {
                console.error("Failed to load profile data", err);
                setError("Failed to load profile details. Please try again.");
            } finally {
                setLoading(false);
            }
        };

        loadProfileAndLookups();
    }, []);

    // Filter available services based on single selected industry
    const filteredServices = useMemo(() => {
        if (!selectedIndustry) return [];
        return allServices.filter(
            (s) => !s.industry_id || s.industry_id === selectedIndustry.id,
        );
    }, [allServices, selectedIndustry]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError(null);
        setSubmitting(true);

        try {
            const payload = {
                business_name: businessName,
                contact_person: contactPerson,
                address: address,
                gst_number: gstNumber,
                industry_ids: selectedIndustry ? [selectedIndustry.id] : [],
                service_ids: selectedServices.map((s) => s.id),
            };

            await vendorApi.updateProfile(payload);
            setSuccessMsg("Profile updated successfully!");
            setTimeout(() => {
                navigate("/dashboard");
            }, 1200);
        } catch (err: any) {
            console.error("Update profile error", err);
            setError(
                err.response?.data?.message ||
                    "Failed to update profile. Please check input values.",
            );
        } finally {
            setSubmitting(false);
        }
    };

    if (loading) {
        return (
            <Box
                sx={{
                    minHeight: "100vh",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    background: "#0f172a",
                }}
            >
                <CircularProgress size={48} color="primary" />
            </Box>
        );
    }

    return (
        <Box
            sx={{
                minHeight: "100vh",
                background:
                    "linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%)",
                color: "#ffffff",
                pt: { xs: "96px", sm: "80px" },
                pb: 6,
            }}
        >
            {/* Top Fixed Navigation Header */}
            <Box
                sx={{
                    position: "fixed",
                    top: 0,
                    left: 0,
                    right: 0,
                    zIndex: 1200,
                    width: "100%",
                    boxSizing: "border-box",
                    background: "rgba(30, 41, 59, 0.95)",
                    backdropFilter: "blur(16px)",
                    borderBottom: "1px solid rgba(255, 255, 255, 0.1)",
                    pt: { xs: 5, sm: 3 },
                    pb: 2,
                    px: { xs: 2, sm: 4 },
                }}
            >
                <Container maxWidth="md" disableGutters>
                    <Box sx={{ display: "flex", alignItems: "center", gap: 2 }}>
                        <IconButton
                            color="inherit"
                            onClick={() => navigate(-1)}
                            size="medium"
                        >
                            <ArrowBackIcon />
                        </IconButton>
                        <Typography variant="h6" sx={{ fontWeight: 800 }}>
                            Edit Profile
                        </Typography>
                    </Box>
                </Container>
            </Box>

            {/* Main Form Container */}
            <Container maxWidth="sm" sx={{ mt: 2 }}>
                <Paper
                    elevation={16}
                    sx={{
                        padding: { xs: 3, sm: 4 },
                        borderRadius: 4,
                        backdropFilter: "blur(16px)",
                        background: "rgba(30, 41, 59, 0.85)",
                        border: "1px solid rgba(255, 255, 255, 0.1)",
                    }}
                >
                    <Typography variant="h5" sx={{ fontWeight: 800, mb: 1 }}>
                        Edit Profile
                    </Typography>
                    <Typography
                        variant="body2"
                        color="text.secondary"
                        sx={{ mb: 3 }}
                    >
                        Update your business information and service offerings
                        below.
                    </Typography>

                    {error && (
                        <Alert severity="error" sx={{ mb: 3, borderRadius: 2 }}>
                            {error}
                        </Alert>
                    )}

                    <Box component="form" onSubmit={handleSubmit} noValidate>
                        <Stack spacing={2.5}>
                            {/* Business Name */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    Business Name:
                                </Typography>
                                <TextField
                                    fullWidth
                                    required
                                    placeholder="Ramesh Karigar Works"
                                    value={businessName}
                                    onChange={(e) =>
                                        setBusinessName(e.target.value)
                                    }
                                />
                            </Box>

                            {/* Contact Person */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    Contact Person:
                                </Typography>
                                <TextField
                                    fullWidth
                                    placeholder="Ramesh Sharma"
                                    value={contactPerson}
                                    onChange={(e) =>
                                        setContactPerson(e.target.value)
                                    }
                                />
                            </Box>

                            {/* Address */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    Address:
                                </Typography>
                                <TextField
                                    fullWidth
                                    multiline
                                    rows={2}
                                    placeholder="MI Road, Jaipur"
                                    value={address}
                                    onChange={(e) => setAddress(e.target.value)}
                                />
                            </Box>

                            {/* GST Number */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    GST Number:
                                </Typography>
                                <TextField
                                    fullWidth
                                    placeholder="08ABCDE1234F1Z5"
                                    value={gstNumber}
                                    onChange={(e) =>
                                        setGstNumber(e.target.value)
                                    }
                                />
                            </Box>

                            <Divider
                                sx={{
                                    my: 1,
                                    borderColor: "rgba(255, 255, 255, 0.1)",
                                }}
                            />

                            {/* Industry Single Select */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    Industry:
                                </Typography>
                                <Autocomplete<Industry, false, false, false>
                                    options={allIndustries}
                                    getOptionLabel={(option) => option.name}
                                    value={selectedIndustry}
                                    onChange={(_, newValue) =>
                                        setSelectedIndustry(newValue)
                                    }
                                    isOptionEqualToValue={(option, val) =>
                                        option.id === val.id
                                    }
                                    renderInput={(params) => (
                                        <TextField
                                            {...params}
                                            placeholder="Select Industry..."
                                        />
                                    )}
                                />
                            </Box>

                            {/* Services Multiselect */}
                            <Box>
                                <Typography
                                    variant="caption"
                                    sx={{
                                        fontWeight: 700,
                                        color: "#94a3b8",
                                        mb: 0.5,
                                        display: "block",
                                    }}
                                >
                                    Services:
                                </Typography>
                                <Autocomplete<Service, true, false, false>
                                    multiple
                                    options={filteredServices}
                                    getOptionLabel={(option) => option.name}
                                    value={selectedServices}
                                    onChange={(_, newValue) =>
                                        setSelectedServices(newValue)
                                    }
                                    isOptionEqualToValue={(option, val) =>
                                        option.id === val.id
                                    }
                                    disabled={!selectedIndustry}
                                    renderOption={(
                                        props,
                                        option,
                                        { selected },
                                    ) => (
                                        <li {...props} key={option.id}>
                                            <Checkbox
                                                style={{ marginRight: 8 }}
                                                checked={selected}
                                            />
                                            {option.name}
                                        </li>
                                    )}
                                    renderInput={(params) => (
                                        <TextField
                                            {...params}
                                            placeholder={
                                                selectedIndustry
                                                    ? "Select Services..."
                                                    : "Select an industry first"
                                            }
                                        />
                                    )}
                                />
                            </Box>

                            {/* Action Button */}
                            <Button
                                fullWidth
                                type="submit"
                                variant="contained"
                                size="large"
                                disabled={submitting}
                                startIcon={
                                    submitting ? (
                                        <CircularProgress
                                            size={20}
                                            color="inherit"
                                        />
                                    ) : (
                                        <SaveIcon />
                                    )
                                }
                                sx={{
                                    mt: 2,
                                    py: 1.6,
                                    fontSize: "1rem",
                                    fontWeight: 700,
                                    borderRadius: 3,
                                    background:
                                        "linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)",
                                    boxShadow:
                                        "0 8px 20px rgba(79, 70, 229, 0.4)",
                                    "&:hover": {
                                        background:
                                            "linear-gradient(135deg, #4f46e5 0%, #4338ca 100%)",
                                    },
                                }}
                            >
                                {submitting
                                    ? "Saving Changes..."
                                    : "Save Changes"}
                            </Button>
                        </Stack>
                    </Box>
                </Paper>
            </Container>

            {/* Notification Snackbar */}
            <Snackbar
                open={!!successMsg}
                autoHideDuration={3000}
                onClose={() => setSuccessMsg(null)}
                anchorOrigin={{ vertical: "bottom", horizontal: "center" }}
            >
                <Alert
                    severity="success"
                    sx={{ width: "100%", borderRadius: 2 }}
                >
                    {successMsg}
                </Alert>
            </Snackbar>
        </Box>
    );
};

export default EditProfile;
