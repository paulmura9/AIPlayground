import { FC, useEffect, useState } from "react";
import "./Platforms.css";
import { Platform } from "../shared/types/Platform";
import { PlatformModel } from "../../api/Models/PlatformModel";
import { ModelModel } from "../../api/Models/ModelModel";
import { Model } from "../shared/types/Model";
import { PlatformsApiClient } from "../../api/Clients/PlatformsApiClient";
import { Stack, CircularProgress, Box, Grid, Card, CardMedia, CardContent, Typography, Paper, List, ListItem, ListItemText, Rating } from "@mui/material";

export const Platforms: FC = () => {
  const [platforms, setPlatforms] = useState<Platform[]>([]);
  const [loading, setLoading] = useState<boolean>(false);
  const fetchPlatforms = async () => {
    try {
      setLoading(true);
      const res = await PlatformsApiClient.getAllAsync();

      const fetchedPlatforms = res.map(
        (e: PlatformModel): Platform => ({
          id: e.id!,
          name: e.name,
          imageUrl: e.imageUrl,
          models: e.models.map((model: ModelModel): Model => ({ id: model.id!, name: model.name, userRating: model.userRating, rating: model.rating }))

        })
      )
      setPlatforms(fetchedPlatforms);
    } catch (error: any) {
      console.log(error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchPlatforms();
  }, [])

  if (loading) {
    return (
      <Stack justifyContent="center" alignItems="center" height="80vh">
        <CircularProgress size={60} />
      </Stack>
    );
  }

  return (
    <Box p={4}>
      <Grid container spacing={4}>
        {platforms.map((platform) => (
          <Grid size={{ xs: 12, sm: 6, md: 4 }} key={platform.id}>
            <Card className={"platforms-card"}>
              <CardMedia
                component="img"
                height="350"
                src={platform.imageUrl}
                alt={platform.name}
                className={"platforms-card-media"}
              />
              <CardContent>
                <Typography variant="h6" component="div" gutterBottom>
                  {platform.name}
                </Typography>
                <Paper
                  variant="outlined"
                  sx={{ maxHeight: 160, overflow: "auto" }}
                >
                  <List dense>
                    {Object.values(platform.models).map((model) => (
                      <ListItem key={model.id}>
                        <ListItemText
                          primary={model.name}
                          secondary={`Rating: ${model.rating}`}
                        />
                      </ListItem>
                    ))}
                  </List>
                </Paper>
              </CardContent>
            </Card>
          </Grid>
        ))}
      </Grid>
    </Box>
  );
};
